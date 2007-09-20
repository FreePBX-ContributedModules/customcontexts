--

-- 
-- Database: `asterisk`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `customcontexts_contexts`
-- 

CREATE TABLE IF NOT EXISTS `customcontexts_contexts` (
  `context` varchar(100) NOT NULL default '',
  `description` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`context`),
  UNIQUE KEY `description` (`description`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `customcontexts_contexts_list`
-- 

CREATE TABLE IF NOT EXISTS `customcontexts_contexts_list` (
  `context` varchar(100) NOT NULL default '',
  `description` varchar(100) NOT NULL default '',
  `locked` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`context`),
  UNIQUE KEY `description` (`description`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `customcontexts_contexts_list`
-- 

INSERT IGNORE INTO `customcontexts_contexts_list` (`context`, `description`, `locked`) VALUES ('from-internal', 'Default Internal Context', 1),
('from-internal-additional', 'Internal Dialplan', 0),
('outbound-allroutes', 'Outbound Routes', 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `customcontexts_includes`
-- 

CREATE TABLE IF NOT EXISTS `customcontexts_includes` (
  `context` varchar(100) NOT NULL default '',
  `include` varchar(100) NOT NULL default '',
  `timegroupid` int(11) default NULL,
  `sort` int(11) NOT NULL default '0',
  PRIMARY KEY  (`context`,`include`),
  KEY `sort` (`sort`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

ALTER IGNORE TABLE `customcontexts_includes` ADD `timegroupid` INT NULL AFTER `include` ;

-- --------------------------------------------------------

-- 
-- Table structure for table `customcontexts_includes_list`
-- 

CREATE TABLE IF NOT EXISTS `customcontexts_includes_list` (
  `context` varchar(100) NOT NULL default '',
  `include` varchar(100) NOT NULL default '',
  `description` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`context`,`include`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

ALTER IGNORE TABLE `customcontexts_includes_list` ADD `missing` BOOL NOT NULL DEFAULT '0';

-- 
-- Dumping data for table `customcontexts_includes_list`
-- 

INSERT IGNORE INTO `customcontexts_includes_list` (`context`, `include`, `description`) VALUES ('from-internal', 'parkedcalls', 'Call Parking'),
('from-internal', 'from-internal-custom', 'Custom Internal Dialplan'),
('from-internal', 'ext-fax', 'Fax');

INSERT IGNORE INTO `customcontexts_includes_list` (`context`, `include`, `description`) VALUES ('from-internal-additional', 'outbound-allroutes', 'ALL OUTBOUND ROUTES'),
('from-internal', 'from-internal-additional', 'ENTIRE Basic Internal Dialplan');

UPDATE `customcontexts_includes_list` SET `description` = 'ALL OUTBOUND ROUTES' WHERE  `context` = 'from-internal-additional' AND `include` = 'outbound-allroutes';

-- --------------------------------------------------------

-- 
-- Table structure for table `customcontexts_module`
-- 

CREATE TABLE IF NOT EXISTS `customcontexts_module` (
  `id` varchar(50) NOT NULL default '',
  `value` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `customcontexts_module`
-- 

INSERT IGNORE INTO `customcontexts_module` (`id`, `value`) VALUES ('modulerawname', 'customcontexts'),
('moduledisplayname', 'Custom Contexts'),
('moduleversion', '0.3.2'),
('displaysortforincludes', 1);

UPDATE `customcontexts_module` set `value` = '0.3.2' where `id` = 'moduleversion';

-- --------------------------------------------------------

-- 
-- Table structure for table `customcontexts_timegroups`
-- 

CREATE TABLE IF NOT EXISTS `customcontexts_timegroups` (
  `id` int(11) NOT NULL auto_increment,
  `description` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `display` (`description`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `customcontexts_timegroups_detail`
-- 

CREATE TABLE IF NOT EXISTS `customcontexts_timegroups_detail` (
  `id` int(11) NOT NULL auto_increment,
  `timegroupid` int(11) NOT NULL default '0',
  `time` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;
