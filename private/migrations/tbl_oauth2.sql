CREATE TABLE `tbl_oauth2` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `service` varchar(45) DEFAULT NULL,
  `email` varchar(320) DEFAULT NULL,
  `access_token` varchar(1024) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `session_id` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
