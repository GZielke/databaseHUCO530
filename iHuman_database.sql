DROP TABLE IF EXISTS user;
CREATE TABLE user (
	id tinyint unsigned NOT NULL auto_increment PRIMARY KEY,
	firstName varchar(30) NOT NULL,
	lastName varchar(30) NOT NULL,
	dateOfBirth DATE,
	username varchar(30) NOT NULL,
	password varchar(255) NOT NULL
);
INSERT INTO user VALUES (NULL,'admin','admin','1911-11-11','admin','password');

DROP TABLE IF EXISTS clock;
CREATE TABLE clock (
	id tinyint unsigned NOT NULL auto_increment PRIMARY KEY,
	username tinyint NOT NULL,
	timeIn timestamp DEFAULT 0,
	timeOut timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

DROP TABLE IF EXISTS eventCompletion;
CREATE TABLE eventCompletion (
	id tinyint unsigned NOT NULL auto_increment PRIMARY KEY,
	username tinyint NOT NULL,
	eventName tinyint NOT NULL,
	dateComplete date NOT NULL,
	journal text
);

DROP TABLE IF EXISTS event;
CREATE TABLE event (
	id tinyint unsigned NOT NULL auto_increment PRIMARY KEY,
	username tinyint NOT NULL,
	eventName varchar(50) NOT NULL,
	eventCategory varchar(30) NOT NULL,
	eventLocation varchar(30) NOT NULL,
	points int NOT NULL
);

DROP TABLE IF EXISTS mtmRegistry;
CREATE TABLE mtmRegistry (
	id tinyint unsigned NOT NULL auto_increment PRIMARY KEY,
	username tinyint NOT NULL,
	dateComplete date NOT NULL,
	mood tinyint NOT NULL,
	journal text
);