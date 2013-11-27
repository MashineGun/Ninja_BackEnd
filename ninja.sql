CREATE TABLE IF NOT EXISTS `devices` 
  ( 
     `id`          BIGINT(20) NOT NULL auto_increment, 
     `device`      VARCHAR(100) NOT NULL, 
     `type`        VARCHAR(20) NOT NULL, 
     `goal_device` VARCHAR(100) NOT NULL, 
     `on_goes`     VARCHAR(10) NOT NULL, 
     `guid`        VARCHAR(100) NOT NULL, 
     `short_name`  VARCHAR(100) NOT NULL, 
     `da`          VARCHAR(100) NOT NULL, 
     KEY `id` (`id`) 
  ) 
engine=innodb 
DEFAULT charset=latin1 
auto_increment=28;

INSERT INTO `devices`(`id`,`device`,`type`,`goal_device`,`on_goes`,`guid`,
`short_name`,`da`)VALUES(1,'Eyes','Actuator','','','1313BB000553_0_0_1007',
'black','000000'),(2,'Eyes','Actuator','','','1313BB000553_0_0_1007','white',
'FFFFFF'),(3,'Eyes','Actuator','','','1313BB000553_0_0_1007','red','FF0000'),(4,
'Eyes','Actuator','','','1313BB000553_0_0_1007','orange','FF5D00'),(5,'Eyes',
'Actuator','','','1313BB000553_0_0_1007','light orange','FFBF00'),(6,'Eyes',
'Actuator','','','1313BB000553_0_0_1007','yellow','E2FF00'),(7,'Eyes','Actuator'
,'','','1313BB000553_0_0_1007','light green','80FF00'),(8,'Eyes','Actuator','',
'','1313BB000553_0_0_1007','green','22FF00'),(9,'Eyes','Actuator','','',
'1313BB000553_0_0_1007','green blue','00FF40'),(10,'Eyes','Actuator','','',
'1313BB000553_0_0_1007','blue green','00FF9D'),(11,'Eyes','Actuator','','',
'1313BB000553_0_0_1007','light blue','00FFFF'),(12,'Eyes','Actuator','','',
'1313BB000553_0_0_1007','blue','00A2FF'),(13,'Eyes','Actuator','','',
'1313BB000553_0_0_1007','ocean blue','003FFF'),(14,'Eyes','Actuator','','',
'1313BB000553_0_0_1007','dark blue','1D00FF'),(15,'Eyes','Actuator','','',
'1313BB000553_0_0_1007','purple','7F00FF'),(16,'Eyes','Actuator','','',
'1313BB000553_0_0_1007','light purple','DD00FF'),(17,'Eyes','Actuator','','',
'1313BB000553_0_0_1007','pink','FF00BF'),(18,'Eyes','Actuator','','',
'1313BB000553_0_0_1007','dark pink','FF0062'),(25,'Temperature','Sensor','','',
'1313BB000553_0101_0_31','',''),(26,'Humidity','Sensor','','',
'1313BB000553_0101_0_30','',''),(27,'Remote','Sensor','','',
'1313BB000553_0_0_11','On','011101010111010100110000');

CREATE TABLE IF NOT EXISTS `logs` 
  ( 
     `id`         BIGINT(20) NOT NULL auto_increment, 
     `login`      VARCHAR(20) NOT NULL, 
     `action`     TEXT NOT NULL, 
     `device`     VARCHAR(20) NOT NULL, 
     `short_name` VARCHAR(20) NOT NULL, 
     `da`         VARCHAR(100) NOT NULL, 
     `timestamp`  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
     PRIMARY KEY (`id`) 
  ) 
engine=innodb 
DEFAULT charset=latin1 
auto_increment=1; 

CREATE TABLE IF NOT EXISTS `status` 
  ( 
     `id`          BIGINT(20) NOT NULL auto_increment, 
     `device`      VARCHAR(100) NOT NULL, 
     `type`        VARCHAR(20) NOT NULL, 
     `guid`        VARCHAR(30) NOT NULL, 
     `login`       VARCHAR(100) NOT NULL, 
     `short_name`  VARCHAR(100) NOT NULL, 
     `da`          VARCHAR(100) NOT NULL, 
     `goal`        VARCHAR(100) NOT NULL, 
     `goal_device` VARCHAR(100) NOT NULL, 
     `on_goes`     VARCHAR(10) NOT NULL, 
     `start`       BIGINT(20) NOT NULL DEFAULT '0', 
     `end`         BIGINT(20) NOT NULL DEFAULT '0', 
     `updated_on`  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
     PRIMARY KEY (`id`) 
  ) 
engine=innodb 
DEFAULT charset=latin1 
auto_increment=8; 


INSERT INTO `status` 
            (`id`, 
             `device`, 
             `type`, 
             `guid`, 
             `login`, 
             `short_name`, 
             `da`, 
             `goal`, 
             `goal_device`, 
             `on_goes`, 
             `start`, 
             `end`, 
             `updated_on`) 
VALUES      (1, 
             'Eyes', 
             'Actuator', 
             '1313BB000553_0_0_1007', 
             'admin', 
             'black', 
             '000000', 
             '', 
             '', 
             '', 
             0, 
             0, 
             '2013-11-11 09:12:43'), 
            (5, 
             'Temperature', 
             'Sensor', 
             '1313BB000553_0101_0_31', 
             'admin', 
             '12.4', 
             '12.4', 
             '', 
             '', 
             '', 
             0, 
             0, 
             '2013-11-11 09:16:43'), 
            (6, 
             'Humidity', 
             'Sensor', 
             '1313BB000553_0101_0_30', 
             'admin', 
             '68', 
             '68', 
             '', 
             '', 
             '', 
             0, 
             0, 
             '2013-11-11 09:16:33'), 
            (7, 
             'Remote', 
             'Sensor', 
             '1313BB000553_0_0_11', 
             'admin', 
             'On', 
             '011101010111010100110000', 
             '', 
             '', 
             '', 
             0, 
             0, 
             '2013-11-11 09:02:41'); 

CREATE TABLE IF NOT EXISTS `users` 
  ( 
     `id`       BIGINT(20) NOT NULL auto_increment, 
     `login`    VARCHAR(20) NOT NULL, 
     `password` VARCHAR(50) NOT NULL, 
     `token`    VARCHAR(50) NOT NULL DEFAULT '', 
     `role`     VARCHAR(20) NOT NULL DEFAULT 'demo', 
     PRIMARY KEY (`id`) 
  ) 
engine=innodb 
DEFAULT charset=latin1 
auto_increment=6; 

INSERT INTO `users` 
            (`id`, 
             `login`, 
             `password`, 
             `token`, 
             `role`) 
VALUES      (1, 
             'admin', 
             '5f4dcc3b5aa765d61d8327deb882cf99', 
             '', 
             'admin');