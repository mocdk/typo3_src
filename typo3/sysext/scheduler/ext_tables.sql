#
# Table structure for table 'tx_scheduler_task'
#
CREATE TABLE tx_scheduler_task (
	uid int(11) unsigned DEFAULT '0' NOT NULL auto_increment,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	disable tinyint(4) unsigned DEFAULT '0' NOT NULL,
	description text NOT NULL,
	nextexecution int(11) unsigned DEFAULT '0' NOT NULL,
	lastexecution_time int(11) unsigned DEFAULT '0' NOT NULL,
	lastexecution_failure text NOT NULL,
	lastexecution_context char(3) DEFAULT '' NOT NULL,
	serialized_task_object blob,
	serialized_executions blob,
	task_group int(11) unsigned DEFAULT '0' NOT NULL,
	PRIMARY KEY (uid),
	KEY index_nextexecution (nextexecution)
);

#
# Table structure for table 'tx_scheduler_task_group'
#
CREATE TABLE tx_scheduler_task_group (
	uid int(11) unsigned DEFAULT '0' NOT NULL auto_increment,
	pid int(11) unsigned DEFAULT '0' NOT NULL,
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	groupName varchar(80) DEFAULT '' NOT NULL,
	description text NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid)
);