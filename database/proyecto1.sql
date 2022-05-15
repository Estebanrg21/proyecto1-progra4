DROP DATABASE IF EXISTS proyecto1;
CREATE DATABASE proyecto1;

DROP USER IF EXISTS 'proyectoAdmin'@'%';
DROP USER IF EXISTS 'proyectoAdmin'@'localhost';
CREATE USER 'proyectoAdmin'@'%' IDENTIFIED BY 'root123';
CREATE USER 'proyectoAdmin'@'localhost' IDENTIFIED BY 'root123';
GRANT ALL PRIVILEGES ON proyecto1.* TO 'proyectoAdmin'@'%';
GRANT ALL PRIVILEGES ON proyecto1.* TO 'proyectoAdmin'@'localhost';
USE proyecto1;

CREATE TABLE users (
  username varchar(10) NOT NULL,
  password varchar(50) NOT NULL,
  PRIMARY KEY (username)
);

CREATE TABLE countries(
    id bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL    
);


CREATE TABLE teams(
    id bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    country_id bigint NOT NULL,
    INDEX(country_id),
    FOREIGN KEY (country_id) REFERENCES countries(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE tgroups(
    name VARCHAR(5) NOT NULL,
    edition int NOT NULL,
    PRIMARY KEY (name,edition)

);

CREATE TABLE group_teams(
    id bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
    team_id bigint NOT NULL,
    g_name VARCHAR(5) NOT NULL,
    g_edition int NOT NULL,
    unique (team_id,g_name,g_edition),
    FOREIGN KEY (team_id) REFERENCES teams(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (g_name) REFERENCES tgroups(name) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (g_edition) REFERENCES tgroups(edition) ON UPDATE CASCADE ON DELETE CASCADE

);

CREATE TABLE matches(
    id bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
    local_team bigint NOT NULL,
    visit_team bigint NOT NULL,
    g_name VARCHAR(5) NOT NULL,
    g_edition int NOT NULL,
    FOREIGN KEY (local_team) REFERENCES teams(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (visit_team) REFERENCES teams(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (g_name) REFERENCES tgroups(name) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (g_edition) REFERENCES tgroups(edition) ON UPDATE CASCADE ON DELETE CASCADE,
    INDEX (local_team),
    INDEX (visit_team)
);

CREATE TABLE match_details(
    id bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
    team_id bigint NOT NULL,  
    match_id bigint NOT NULL,
    goals_favor int NOT NULL,
    goals_against int NOT NULL,
    result VARCHAR(5) NOT NULL,
    INDEX (match_id),
    INDEX (team_id),
    FOREIGN KEY (team_id) REFERENCES teams(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (match_id) REFERENCES matches(id) ON UPDATE CASCADE ON DELETE CASCADE
);

INSERT INTO users VALUES ('admin',md5('admin'));