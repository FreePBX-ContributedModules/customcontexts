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

$modinfo = module_getinfo('customcontexts');
if (is_array($modinfo)) {
	$ver = $modinfo['customcontexts']['dbversion'];
	if (version_compare($ver,'0.3.6','le')) {
    global $db;
    $notifications =& notifications::create($db); 
    $extext = _("The Custom Context 3rd Party module will not be supported in FreePBX version 2.8 or beyond without your financial support. If this module is critical to you or your business, please refer to the following URL to assure it does not go End of Life:<br />http://www.freepbx.org/bounties/custom-context");
    $notifications->add_notice('customcontexts', 'EOL', ('Custom Context END OF LIFE Warning'), $extext, '', true, true);
  }
}
?>
