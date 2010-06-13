<?php /* $Id: install.php $ */
//
//This program is free software; you can redistribute it and/or
//modify it under the terms of the GNU General Public License
//as published by the Free Software Foundation; either version 2
//of the License, or (at your option) any later version.
//
//This program is distributed in the hope that it will be useful,
//but WITHOUT ANY WARRANTY; without even the implied warranty of
//MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//GNU General Public License for more details.
?>
<!--
<font color="red"><strong>You have installed the Custom Contexts Module!<BR>
	</strong></font><BR>
-->
<?php

global $db;
global $amp_conf;

if (! function_exists("out")) {
	function out($text) {
		echo $text."<br />";
	}
}

if (! function_exists("outn")) {
	function outn($text) {
		echo $text;
	}
}


$sql[] ="CREATE TABLE IF NOT EXISTS `customcontexts_contexts` (
			  `context` varchar(100) NOT NULL default '',
			  `description` varchar(100) NOT NULL default '',
			  PRIMARY KEY  (`context`),
			  UNIQUE KEY `description` (`description`)
			)";


$sql[] ="CREATE TABLE IF NOT EXISTS `customcontexts_contexts_list` (
				`context` varchar(100) NOT NULL default '',
				`description` varchar(100) NOT NULL default '',
				`locked` tinyint(1) NOT NULL default '0',
				PRIMARY KEY  (`context`),
				UNIQUE KEY `description` (`description`)
				)";

$sql[] ="INSERT IGNORE INTO `customcontexts_contexts_list` 
				(`context`, `description`, `locked`) 
				VALUES ('from-internal', 'Default Internal Context', 1),
				('from-internal-additional', 'Internal Dialplan', 0),('outbound-allroutes', 'Outbound Routes', 0)";

$sql[] ="CREATE TABLE IF NOT EXISTS `customcontexts_includes` (
  `context` varchar(100) NOT NULL default '',
  `include` varchar(100) NOT NULL default '',
  `timegroupid` int(11) default NULL,
  `sort` int(11) NOT NULL default '0',
  PRIMARY KEY  (`context`,`include`),
  KEY `sort` (`sort`)
)";

$sql[] ="ALTER IGNORE TABLE `customcontexts_includes` ADD `timegroupid` INT NULL AFTER `include`";

$sql[] ="CREATE TABLE IF NOT EXISTS `customcontexts_includes_list` (
  `context` varchar(100) NOT NULL default '',
  `include` varchar(100) NOT NULL default '',
  `description` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`context`,`include`)
)";

$sql[] ="ALTER IGNORE TABLE `customcontexts_includes_list` ADD `missing` BOOL NOT NULL DEFAULT '0'";


$sql[] ="INSERT IGNORE INTO `customcontexts_includes_list` (`context`, `include`, `description`) VALUES ('from-internal', 'parkedcalls', 'Call Parking'),
('from-internal', 'from-internal-custom', 'Custom Internal Dialplan'),
('from-internal', 'ext-fax', 'Fax')";

$sql[] ="INSERT IGNORE INTO `customcontexts_includes_list` (`context`, `include`, `description`) VALUES ('from-internal-additional', 'outbound-allroutes', 'ALL OUTBOUND ROUTES'),
('from-internal', 'from-internal-additional', 'ENTIRE Basic Internal Dialplan')";

$sql[] ="UPDATE `customcontexts_includes_list` SET `description` = 'ALL OUTBOUND ROUTES' WHERE  `context` = 'from-internal-additional' AND `include` = 'outbound-allroutes'";

$sql[] ="CREATE TABLE IF NOT EXISTS `customcontexts_module` (
  `id` varchar(50) NOT NULL default '',
  `value` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`)
)";

$sql[] ="INSERT IGNORE INTO `customcontexts_module` (`id`, `value`) VALUES ('modulerawname', 'customcontexts'),
				('moduledisplayname', 'Custom Contexts'),
				('moduleversion', '0.3.2'),
				('displaysortforincludes', 1)";

$sql[] ="UPDATE `customcontexts_module` set `value` = '0.3.2' where `id` = 'moduleversion';";

$sql[] ="CREATE TABLE IF NOT EXISTS `customcontexts_timegroups` (
  `id` int(11) NOT NULL auto_increment,
  `description` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `display` (`description`)
) AUTO_INCREMENT=4 ";

$sql[] ="CREATE TABLE IF NOT EXISTS `customcontexts_timegroups_detail` (
  `id` int(11) NOT NULL auto_increment,
  `timegroupid` int(11) NOT NULL default '0',
  `time` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`)
) AUTO_INCREMENT=20";

foreach ($sql as $q){
	$db->query($sql);
}

customcontexts_updatedb();

//bring db up to date on install/upgrade
function customcontexts_updatedb() {
	global $db;
	$sql = "ALTER IGNORE TABLE `customcontexts_includes` ADD `timegroupid` INT NULL AFTER `include` ;";
	$db->query($sql);
	$sql = "ALTER IGNORE TABLE `customcontexts_includes_list` ADD `missing` BOOL NOT NULL DEFAULT '0';";
	$db->query($sql);
	$sql = "ALTER IGNORE TABLE `customcontexts_contexts` ADD `dialrules` VARCHAR( 1000 ) NULL;";
	$db->query($sql);
	$sql = "ALTER IGNORE TABLE `customcontexts_includes` ADD `userules` VARCHAR( 10 ) NULL ;";
	$db->query($sql);
	$sql = "ALTER IGNORE TABLE `customcontexts_contexts` ADD `faildestination` VARCHAR( 250 ) NULL , ADD `featurefaildestination` VARCHAR( 250 ) NULL ;";
	$db->query($sql);
//0.3.0
	$sql = "ALTER IGNORE TABLE `customcontexts_contexts` ADD `failpin` VARCHAR( 100 ) NULL , ADD `failpincdr` BOOL NOT NULL DEFAULT '0', ADD `featurefailpin` VARCHAR( 100 ) NULL , ADD `featurefailpincdr` BOOL NOT NULL DEFAULT '0';";
	$db->query($sql);
//0.3.2
	$sql = "ALTER IGNORE TABLE `customcontexts_includes_list` ADD `sort` INT NOT NULL DEFAULT '0';";
	$db->query($sql);
}

outn(_("checking if migration required..."));
$modinfo = module_getinfo('customcontexts');
if (is_array($modinfo)) {
	$ver = $modinfo['customcontexts']['dbversion'];
	if (version_compare($ver,'0.3.6','le')) {
			outn(_("migrating.."));
		  /* We need to now migrate from from the old format of dispname_id where the only supported dispname
		     so far has been "routing" and the "id" used was the imperfect nnn-name. As it truns out, it was
		     possible to have the same route name perfiously so we will try to detect that. This was really ugly
		     so if we can't find stuff we will simply report errors and let the user go back and fix things.
		   */
		  $sql = "SELECT * FROM customcontexts_includes_list WHERE context = 'outbound-allroutes'";
		  $includes = $db->getAll($sql, DB_FETCHMODE_ASSOC);
		  if(DB::IsError($result) || !isset($includes)) { 
		    out(_("Unknown error fetching table data or no data to migrate"));
		    out(_("Migration aborted"));
		  } else {
		    /* If there are any rows then lets get our route information. We will force this module to depend on
		     * the new core, so we can count on the APIs being available. If there are indentical names, then
		     * oh well...
		     */
		    $routes = core_routing_list();
		    $newincludes = array();
		    foreach ($includes as $myinclude) {
		    	$include = explode('-',$myinclude['include'],3);
		    	$include[1] = (int)$include[1];
		    	
		    	foreach ($routes as $route) {
		    		//if we have a trunk with the same name or the same number mathc it and take it out of the list
						if ($include[2] == $route['name'] || $include[1] == $route['route_id']){
							$newincludes[] = array('new' => 'outrt-'.$route['route_id'], 
																	'sort' => $route['seq'], 'old' => $include);
							unset($includes[$myinclude]);
						} 
					}	
		    }

				//alert user of unmigrated routes
				foreach ($includes as $include) {
	        out(_('FAILED to migrating route '.$include['description'].'. NO MATCH FOUND'));
	        outn(_("continuing..."));
	      }

		    // We new have all the indices, so lets save them
		    //
		    $sql = $db->prepare('UPDATE customcontexts_includes_list SET include = ?, sort = ? WHERE include = ?');
		    //$result = $db->executeMultiple($sql,$newincludes);
		    if(DB::IsError($result)) {
		      out("FATAL: ".$result->getDebugInfo()."\n".'error updating customcontexts_includes_list table. Aborting!');	
		    } else {
			    //now update the next table
			    foreach ($newincludes as $newinclude){ 
			    	unset($newincludes[$newinclude]['sort']);
			    }
			    $sql = $db->prepare('UPDATE customcontexts_includes SET include = ?, WHERE include = ?');
			    //$result = $db->executeMultiple($sql,$newincludes);
			    if(DB::IsError($result)) {
			      out("FATAL: ".$result->getDebugInfo()."\n".'error updating customcontexts_includes table. Aborting!');	
			    } else {
			    out(_("done"));			    
			  }
		  }
		}
  }
}


?>
