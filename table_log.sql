DROP TABLE IF EXISTS `log`;
CREATE TABLE  `log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `log_ip` varchar(15) NOT NULL,
  `log_os` varchar(25) NOT NULL,
  `log_browser` varchar(30) NOT NULL,
  `log_type` enum('access', 'activity', 'modify') NOT NULL,
  `log_status` enum('login', 'logout', 'timeout', 'failed_in', 'hack') NOT NULL,
  `log_time` varchar(150) NOT NULL,
  `log_email` varchar(50) NOT NULL,
  `log_password` varchar(40) NOT NULL,
  `log_url` text NOT NULL,
  PRIMARY KEY (`log_id`)
) 
ENGINE=InnoDB;

insert into `log` values (1,'127.0.0.1','Linux','Chrome','access','login','60','kangcahya@gmail.com','kang','www.kang-cahya.com');
