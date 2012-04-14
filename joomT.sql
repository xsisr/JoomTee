

CREATE TABLE IF NOT EXISTS `joomT` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `file` varchar(200) NOT NULL,
  `key` varchar(200) NOT NULL,
  `lang` varchar(200) NOT NULL,
  `translation` varchar(200) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`file`,`key`,`lang`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


