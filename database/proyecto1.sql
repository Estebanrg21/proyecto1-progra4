DROP DATABASE IF EXISTS proyecto1;
CREATE DATABASE proyecto1;

USE proyecto1;

CREATE TABLE users (
  id bigint NOT NULL AUTO_INCREMENT,
  username varchar(10) NOT NULL,
  password varchar(50) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY username_users (username)
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
    id bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL
);

CREATE TABLE group_teams(
    team_id bigint NOT NULL,
    group_id bigint NOT NULL,
    PRIMARY KEY (team_id,group_id),
    FOREIGN KEY (team_id) REFERENCES teams(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (group_id) REFERENCES tgroups(id) ON UPDATE CASCADE ON DELETE CASCADE

);

CREATE TABLE matches(
    id bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
    local_team bigint NOT NULL,
    visit_team bigint NOT NULL,
    FOREIGN KEY (local_team) REFERENCES teams(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (visit_team) REFERENCES teams(id) ON UPDATE CASCADE ON DELETE CASCADE,
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