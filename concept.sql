DROP DATABASE IF EXISTS matchmate_conceptual;
CREATE DATABASE matchmate_conceptual;
USE matchmate_conceptual;

CREATE TABLE leagues(
                        id bigint PRIMARY KEY AUTO_INCREMENT,
                        icon_path varchar(255) NOT NULL,
                        title varchar(255) NOT NULL,
                        description text NOT NULL
);
CREATE TABLE teams(
                      id bigint PRIMARY KEY AUTO_INCREMENT,
                      name varchar(255) NOT NULL,
                      motto varchar(255) NOT NULL,
                      icon_path varchar(255) NOT NULL,
                      banner_path varchar(255) NOT NULL
);
CREATE TABLE leagues_teams(
                              id bigint PRIMARY KEY AUTO_INCREMENT,
                              leagues_id bigint REFERENCES leagues(id),
                              teams_id bigint REFERENCES teams(id)
);
CREATE TABLE matches(
                        id bigint PRIMARY KEY AUTO_INCREMENT,
                        league_id bigint NOT NULL REFERENCES leagues(id),
                        team_id1 bigint NOT NULL REFERENCES teams(id),
                        team_id2 bigint NOT NULL REFERENCES teams(id)
);
CREATE TABLE roles(
                      id bigint PRIMARY KEY AUTO_INCREMENT,
                      title varchar(255) NOT NULL
);
CREATE TABLE users(
                      id bigint PRIMARY KEY AUTO_INCREMENT,
                      team_id bigint REFERENCES teams(id) ON UPDATE CASCADE ON DELETE CASCADE,    is_reserve boolean,
                      email varchar(255) UNIQUE NOT NULL,
                      first_name varchar(255) NOT NULL,
                      last_name varchar(255) NOT NULL,
                      pfp_path varchar(255),
                      role bigint DEFAULT 0 NOT NULL REFERENCES roles(id),
                      password varchar(255) NOT NULL
);
