CREATE TABLE IF NOT EXISTS `Tag`(
   `TNo` INT UNSIGNED AUTO_INCREMENT,
   `TName` VARCHAR(100) NOT NULL,
   `Work` TINYINT,
   PRIMARY KEY ( `TNo` )
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `Goods`(
   `GNo` INT UNSIGNED AUTO_INCREMENT,
   `GName` VARCHAR(100) NOT NULL,
   `Price` FLOAT NOT NULL,
   `Tags` VARCHAR(100),
   `Img` VARCHAR(100),
   `Inventory` INT,
   PRIMARY KEY ( `GNo` )
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `User`(
   `UNo` INT UNSIGNED AUTO_INCREMENT,
   `UName` VARCHAR(100) NOT NULL,
   `Admin` TINYINT NOT NULL,
   `Password` VARCHAR(20) NOT NULL,
   `Img` VARCHAR(100),
   `Email` VARCHAR(30),
   `Tel` VARCHAR(20),
   PRIMARY KEY ( `UNo` )
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `Cart`(
   `CNo` INT UNSIGNED AUTO_INCREMENT,
   `UNo` INT NOT NULL,
   `GNo` INT NOT NULL,
   `Count` INT NOT NULL,
   PRIMARY KEY ( `CNo` )
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `Order`(
   `ONo` INT UNSIGNED AUTO_INCREMENT,
   `UNo` INT NOT NULL,
   `Cost` FLOAT,
   `Time` TIMESTAMP,
   `Status` INT NOT NULL,
   `Address` VARCHAR(100),
   PRIMARY KEY ( `ONo` )
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `Detail`(
   `DNo` INT UNSIGNED AUTO_INCREMENT,
   `ONo` INT NOT NULL,
   `GNo` INT NOT NULL,
   `Count` INT NOT NULL,
   `Cost` FLOAT,
   PRIMARY KEY ( `DNo` )
)ENGINE=InnoDB DEFAULT CHARSET=utf8;