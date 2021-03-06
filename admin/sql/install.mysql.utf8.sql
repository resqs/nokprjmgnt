DROP TABLE IF EXISTS `#__nok_pm_project_comments`;
DROP TABLE IF EXISTS `#__nok_pm_task_comments`;
DROP TABLE IF EXISTS `#__nok_pm_tasks`;
DROP TABLE IF EXISTS `#__nok_pm_projects`;

CREATE TABLE `#__nok_pm_projects` (
  `id` integer NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `description` text default NULL,
  `catid` int(10) NOT NULL default '0',
  `priority` integer NOT NULL default 1,
  `duedate` datetime NOT NULL default '0000-00-00 00:00:00',
  `status` varchar(255) NOT NULL,
  `asset_id` int(10) UNSIGNED NOT NULL default '0',
  `access` int(10) UNSIGNED NOT NULL default '0',
  `custom1` varchar(255) default NULL,
  `custom2` varchar(255) default NULL,
  `custom3` varchar(255) default NULL,
  `custom4` varchar(255) default NULL,
  `custom5` varchar(255) default NULL,
  `createdby` varchar(50) NULL default NULL,
  `createddate` datetime NULL default '0000-00-00 00:00:00',
  `modifiedby` varchar(50) NOT NULL default '',
  `modifieddate` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  CONSTRAINT UC_Projects UNIQUE (`title`),
  UNIQUE (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__nok_pm_tasks` (
  `id` integer NOT NULL auto_increment,
  `project_id` integer NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text default NULL,
  `priority` integer NOT NULL default 1,
  `duedate` datetime NOT NULL default '0000-00-00 00:00:00',
  `status` varchar(255) NOT NULL,
  `progress` integer DEFAULT NULL,
  `responsible_user_id` int(255) UNSIGNED default NULL,
  `assign_user_ids` varchar(255) default NULL,
  `createdby` varchar(50) NULL default NULL,
  `createddate` datetime NULL default '0000-00-00 00:00:00',
  `modifiedby` varchar(50) NOT NULL default '',
  `modifieddate` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  FOREIGN KEY (`project_id`) REFERENCES `#__nok_pm_projects` (`id`) ON DELETE CASCADE,
  UNIQUE (`id`),
  CONSTRAINT UC_Tasks UNIQUE (`project_id`,`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__nok_pm_project_comments` (
  `id` integer NOT NULL auto_increment,
  `project_id` integer NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text default NULL,
  `published` tinyint(3) NOT NULL DEFAULT 0,
  `createdby` varchar(50) NULL default NULL,
  `createddate` datetime NULL default '0000-00-00 00:00:00',
  `modifiedby` varchar(50) NOT NULL default '',
  `modifieddate` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  FOREIGN KEY (`project_id`) REFERENCES `#__nok_pm_projects` (`id`) ON DELETE CASCADE,
  UNIQUE (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__nok_pm_task_comments` (
  `id` integer NOT NULL auto_increment,
  `task_id` integer NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text default NULL,
  `published` tinyint(3) NOT NULL DEFAULT 0,
  `createdby` varchar(50) NULL default NULL,
  `createddate` datetime NULL default '0000-00-00 00:00:00',
  `modifiedby` varchar(50) NOT NULL default '',
  `modifieddate` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  FOREIGN KEY (`task_id`) REFERENCES `#__nok_pm_tasks` (`id`) ON DELETE CASCADE,
  UNIQUE (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

