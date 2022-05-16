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

CREATE TABLE editions(
    id bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL
);

CREATE TABLE teams(
    id bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    country_id bigint NOT NULL,
    edition_id bigint NOT NULL,
    INDEX(country_id),
    FOREIGN KEY (country_id) REFERENCES countries(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (edition_id) REFERENCES editions(id) ON UPDATE CASCADE ON DELETE CASCADE
);


CREATE TABLE tgroups(
    id bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
    name VARCHAR(5) NOT NULL,
    edition_id bigint NOT NULL,
    unique(name,edition_id),
    FOREIGN KEY (edition_id) REFERENCES editions(id) ON UPDATE CASCADE ON DELETE CASCADE

);

CREATE TABLE group_teams(
    id bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
    team_id bigint NOT NULL,
    group_id bigint NOT NULL,
    edition_id bigint NOT NULL,
    unique (team_id,group_id,edition_id),
    FOREIGN KEY (team_id) REFERENCES teams(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (group_id) REFERENCES tgroups(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (edition_id) REFERENCES editions(id) ON UPDATE CASCADE ON DELETE CASCADE

);

CREATE TABLE matches(
    id bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
    local_team bigint NOT NULL,
    visit_team bigint NOT NULL,
    group_id bigint NOT NULL,
    edition_id bigint NOT NULL,
    FOREIGN KEY (local_team) REFERENCES teams(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (visit_team) REFERENCES teams(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (group_id) REFERENCES tgroups(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (edition_id) REFERENCES editions(id) ON UPDATE CASCADE ON DELETE CASCADE,
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
    edition_id bigint NOT NULL,
    INDEX (match_id),
    INDEX (team_id),
    FOREIGN KEY (edition_id) REFERENCES editions(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (team_id) REFERENCES teams(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (match_id) REFERENCES matches(id) ON UPDATE CASCADE ON DELETE CASCADE
);

INSERT INTO users VALUES ('admin',md5('admin'));

INSERT INTO editions VALUES (1, "First edition");
INSERT INTO countries VALUES (1, "Spain");
INSERT INTO countries VALUES (2, "Germany");
INSERT INTO countries VALUES (3, "France");
INSERT INTO countries VALUES (4, "England");
INSERT INTO countries VALUES (5, "Italy");
INSERT INTO countries VALUES (6, "Portugal");
INSERT INTO countries VALUES (7, "Netherlands");
INSERT INTO countries VALUES (8, "Switzerland");

INSERT INTO teams(name,country_id,edition_id) VALUES ("Real Madrid",1,1);
INSERT INTO teams(name,country_id,edition_id) VALUES ("Barcelona",1,1);
INSERT INTO teams(name,country_id,edition_id) VALUES ("Atletico de Madrid",1,1);
INSERT INTO teams(name,country_id,edition_id) VALUES ("Villarreal",1,1);

INSERT INTO teams(name,country_id,edition_id) VALUES ("Bayern Munchen",2,1);
INSERT INTO teams(name,country_id,edition_id) VALUES ("Dortmund",2,1);
INSERT INTO teams(name,country_id,edition_id) VALUES ("Bayern Leverkusen",2,1);
INSERT INTO teams(name,country_id,edition_id) VALUES ("Wolfsburg",2,1);

INSERT INTO teams(name,country_id,edition_id) VALUES ("PSG",3,1);
INSERT INTO teams(name,country_id,edition_id) VALUES ("Olympique Lyon",3,1);
INSERT INTO teams(name,country_id,edition_id) VALUES ("Marseille",3,1);
INSERT INTO teams(name,country_id,edition_id) VALUES ("As Monaco",3,1);

INSERT INTO teams(name,country_id,edition_id) VALUES ("Manchester City",4,1);
INSERT INTO teams(name,country_id,edition_id) VALUES ("Manchester United",4,1);
INSERT INTO teams(name,country_id,edition_id) VALUES ("Liverpool",4,1);
INSERT INTO teams(name,country_id,edition_id) VALUES ("Arsenal",4,1);

INSERT INTO teams(name,country_id,edition_id) VALUES ("Inter",5,1);
INSERT INTO teams(name,country_id,edition_id) VALUES ("AC Milan",5,1);
INSERT INTO teams(name,country_id,edition_id) VALUES ("Juventus",5,1);
INSERT INTO teams(name,country_id,edition_id) VALUES ("Lazio",5,1);

INSERT INTO teams(name,country_id,edition_id) VALUES ("Porto",6,1);
INSERT INTO teams(name,country_id,edition_id) VALUES ("Sporting",6,1);
INSERT INTO teams(name,country_id,edition_id) VALUES ("Benfica",6,1);
INSERT INTO teams(name,country_id,edition_id) VALUES ("Portimonense",6,1);

INSERT INTO teams(name,country_id,edition_id) VALUES ("PSV",7,1);
INSERT INTO teams(name,country_id,edition_id) VALUES ("Ajax",7,1);
INSERT INTO teams(name,country_id,edition_id) VALUES ("AZ Alkmaar",7,1);
INSERT INTO teams(name,country_id,edition_id) VALUES ("Feyenoord",7,1);

INSERT INTO teams(name,country_id,edition_id) VALUES ("Basel",8,1);
INSERT INTO teams(name,country_id,edition_id) VALUES ("Young Boys",8,1);
INSERT INTO teams(name,country_id,edition_id) VALUES ("Lugano",8,1);
INSERT INTO teams(name,country_id,edition_id) VALUES ("Vaduz",8,1);