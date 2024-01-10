USE heroku_81a7cc806491498;

-- Table Country
CREATE TABLE countries (
    id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	name VARCHAR(50) not null
);

-- Table Person
CREATE TABLE persons (
    id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    birth_date DATE NOT NULL,
	is_from INT(11) not null,
	foreign key (is_from) references countries(id)
);

-- Table Agent
CREATE TABLE agents (
    id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    agent_code INT(10) NOT NULL,
    person_id INT(11) NOT NULL,
    FOREIGN KEY (person_id) REFERENCES persons(id)
);



-- Table Target
CREATE TABLE targets (
    id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    code_name VARCHAR(50) NOT NULL,
    person_id INT(11) NOT NULL,
    FOREIGN KEY (person_id) REFERENCES persons(id)
    
);

-- Table Contact
CREATE TABLE contacts (
    id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    code_name VARCHAR(50) NOT NULL,
    person_id INT(11) NOT NULL,
    FOREIGN KEY (person_id) REFERENCES persons(id)
);

-- Table Specialty
CREATE TABLE specialties (
    id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL
);

-- Table AgentSpecialty
CREATE TABLE agent_specialty (
    agent_id INT(11) NOT NULL,
    specialty_id INT(11) NOT NULL,
    PRIMARY KEY (agent_id, specialty_id),
    FOREIGN KEY (agent_id) REFERENCES agents(id),
    FOREIGN KEY (specialty_id) REFERENCES specialties(id)
);

CREATE TABLE types (
    id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT null
);

CREATE TABLE status (
    id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT null
);

-- Table StakeOut
CREATE TABLE stakeouts (
    id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    code INT(10) NOT null,
    address VARCHAR(255) NOT NULL,
    type VARCHAR(50) NOT null,	
    is_located_in INT(11) not null,
	foreign key (is_located_in) references countries(id)
);

-- Table Mission
CREATE TABLE missions (
    id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    code_name VARCHAR(50) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE not NULL,
    mission_type INT(11) not null,
    mission_status INT(11) not null,
    required_specialty INT(11) not null,
    mission_stakeout INT(11) not null,
   	takes_place_in INT(11) not null,
	foreign key (takes_place_in) references countries(id),
    FOREIGN KEY (mission_type) REFERENCES types(id),
    FOREIGN KEY (mission_status) REFERENCES status(id),
    FOREIGN KEY (required_specialty) REFERENCES specialties(id),
    FOREIGN KEY (mission_stakeout) REFERENCES stakeouts(id)
);

-- Table AgentMission
CREATE TABLE agent_mission (
    agent_id INT(11) NOT NULL,
    mission_id INT(11) NOT NULL,
    PRIMARY KEY (agent_id, mission_id),
    FOREIGN KEY (agent_id) REFERENCES agents(id),
    FOREIGN KEY (mission_id) REFERENCES missions(id)
);
-- Table TargetMission
CREATE TABLE target_mission (
    target_id INT(11) NOT NULL,
    mission_id INT(11) NOT NULL,
    PRIMARY KEY (target_id, mission_id),
    FOREIGN KEY (target_id) REFERENCES targets(id),
    FOREIGN KEY (mission_id) REFERENCES missions(id)
);
-- Table ContactMission
CREATE TABLE contact_mission (
    contact_id INT(11) NOT NULL,
    mission_id INT(11) NOT NULL,
    PRIMARY KEY (contact_id, mission_id),
    FOREIGN KEY (contact_id) REFERENCES contacts(id),
    FOREIGN KEY (mission_id) REFERENCES missions(id)
);

-- Table Admin
CREATE TABLE admins (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT null UNIQUE,
    password CHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

