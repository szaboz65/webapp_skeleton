CREATE TABLE `dbupdate` (
  `up_id` int(11) NOT NULL AUTO_INCREMENT,
  `up_version` varchar(16) NOT NULL,
  `up_description` varchar(64) NOT NULL,
  `up_releasedate` datetime DEFAULT NULL,
  `up_updatedate` datetime DEFAULT NULL,
  PRIMARY KEY (`up_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

CREATE TABLE `pass_reset` (
  `userid` int(11) DEFAULT NULL,
  `reset_code` char(64) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `expire` datetime NOT NULL DEFAULT (current_timestamp() + interval 2 day),
  KEY `pw_ibfk_1` (`userid`),
  CONSTRAINT `pw_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `user` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `photo` (
  `userid` int(11) NOT NULL,
  `photo` mediumtext DEFAULT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `role` (
  `roleid` int(11) NOT NULL AUTO_INCREMENT,
  `rolename` varchar(64) NOT NULL,
  PRIMARY KEY (`roleid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

CREATE TABLE `test` (
  `roleid` int(11) NOT NULL AUTO_INCREMENT,
  `rolename` varchar(64) NOT NULL,
  PRIMARY KEY (`roleid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

CREATE TABLE `user` (
  `userid` int(11) NOT NULL AUTO_INCREMENT,
  `fk_utypeid` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `phone` varchar(32) NOT NULL,
  `title` varchar(32) NOT NULL,
  `email` varchar(128) NOT NULL,
  `inactive` tinyint(1) DEFAULT 0,
  `super` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`userid`),
  KEY `user_ibfk_1` (`fk_utypeid`),
  CONSTRAINT `user_ibfk_1` FOREIGN KEY (`fk_utypeid`) REFERENCES `usertype` (`utypeid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

CREATE TABLE `userfail` (
  `fail_userid` int(11) NOT NULL,
  `fail_occured` datetime NOT NULL,
  PRIMARY KEY (`fail_userid`,`fail_occured`),
  KEY `fail_ibfk_1` (`fail_userid`),
  CONSTRAINT `fail_ibfk_1` FOREIGN KEY (`fail_userid`) REFERENCES `user` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `userpref` (
  `upref_id` int(11) NOT NULL,
  `locale` char(32) DEFAULT 'en-US',
  `schema` char(32) DEFAULT 'normal',
  PRIMARY KEY (`upref_id`),
  CONSTRAINT `upref_ibfk_1` FOREIGN KEY (`upref_id`) REFERENCES `user` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

CREATE TABLE `usersecret` (
  `userid` int(11) NOT NULL,
  `expire` datetime NOT NULL,
  `secret` varchar(255) DEFAULT NULL,
  `inactive` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`userid`,`expire`),
  KEY `usec_ibfk_1` (`userid`),
  CONSTRAINT `usec_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `user` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `usersession` (
  `ses_userid` int(11) NOT NULL,
  `ses_lastlogin` datetime NOT NULL,
  `ses_lastactive` datetime NOT NULL,
  `ses_expire` datetime NOT NULL,
  KEY `ses_ibfk_1` (`ses_userid`),
  CONSTRAINT `ses_ibfk_1` FOREIGN KEY (`ses_userid`) REFERENCES `user` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `usertype` (
  `utypeid` int(11) NOT NULL AUTO_INCREMENT,
  `utypename` varchar(64) NOT NULL,
  `roles` int(11) DEFAULT 0,
  PRIMARY KEY (`utypeid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;