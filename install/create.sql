SET NAMES UTF8;

--
-- database: `deploy agent`
--
DROP DATABASE IF EXISTS `deploy_agent`;
CREATE DATABASE deploy_agent CHARACTER SET utf8 COLLATE utf8_general_ci;

--
-- Users
--
CREATE user deploy_agent IDENTIFIED BY 'Yic6SnY0eSk6TEojWWk2UOvpcHX';
GRANT ALL ON deploy_agent.* TO 'deploy_agent'@'localhost' IDENTIFIED BY 'Yic6SnY0eSk6TEojWWk2UOvpcHX';

--
-- Table
--
CREATE TABLE deployment(
	id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	path varchar(100) not null,
	date int not null,
	buildId varchar(100) not null)




