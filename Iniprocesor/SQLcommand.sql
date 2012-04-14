CREATE TABLE `joomT` (
 `file` varchar(200) NOT NULL,
 `key` varchar(200) NOT NULL,
 `lang` varchar(200) NOT NULL,
 `translation` varchar(200) NOT NULL,
  PRIMARY KEY (`file`,`key`,`lang`)  
);
