-- -----------------------------------------------------------------------------
-- Create `hospital` schema
-- -----------------------------------------------------------------------------

CREATE SCHEMA `hospital` COLLATE `utf8mb4_bin`;

-- -----------------------------------------------------------------------------
-- Create `client` table
-- -----------------------------------------------------------------------------

CREATE TABLE `hospital`.`client`
(
    `id`            int AUTO_INCREMENT
        PRIMARY KEY,
    `tax_code`      char(16)    NOT NULL,
    `first_name`    varchar(32) NOT NULL,
    `last_name`     varchar(64) NOT NULL,
    `date_of_birth` date        NOT NULL,
    `gender`        char        NULL,
    CONSTRAINT `client_tax_code_u_index`
        UNIQUE (`tax_code`)
) COMMENT 'The clients of the hospital';

INSERT INTO `hospital`.`client` (`id`, `tax_code`, `first_name`, `last_name`, `date_of_birth`, `gender`)
VALUES (1, 'MRCMRC03E13D286K', 'Marco', 'Mercandalli', '2003-05-13', 'M'),
       (2, 'MROGTV80A01G478S', 'Gustavo', 'Moro', '1980-01-01', 'T'),
       (3, 'CLMCST84E05D969H', 'Cristoforo', 'Colombo', '1984-05-05', NULL),
       (4, 'CRZRNT89R50L219Z', 'Renata', 'Crozza', '1989-10-10', 'F'),
       (5, 'TRCMRA81B42F205E', 'Maria', 'Treccani', '1981-02-02', 'F');

-- -----------------------------------------------------------------------------
-- Create `visit_type` table
-- -----------------------------------------------------------------------------

CREATE TABLE `hospital`.`visit_type`
(
    `id`         int AUTO_INCREMENT
        PRIMARY KEY,
    `visit_type` varchar(64) NOT NULL,
    CONSTRAINT `visit_type_visit_type_u_index`
        UNIQUE (`visit_type`)
) COMMENT 'The consultations offered by the hospital';

INSERT INTO `hospital`.`visit_type` (`id`, `visit_type`)
VALUES (1, 'Dermatologica'),
       (2, 'Ginecologica'),
       (3, 'Oculistica'),
       (4, 'Oncologica'),
       (5, 'Psichiatrica'),
       (6, 'Psicologica'),
       (7, 'Urologica');

-- -----------------------------------------------------------------------------
-- Create `visit` table
-- -----------------------------------------------------------------------------

CREATE TABLE `hospital`.`visit`
(
    `id`            int AUTO_INCREMENT
        PRIMARY KEY,
    `doctor`        varchar(255) NOT NULL,
    `visit_type_id` int          NOT NULL,
    CONSTRAINT `visit_visit_type_id_fk`
        FOREIGN KEY (`visit_type_id`) REFERENCES `hospital`.`visit_type` (`id`)
            ON UPDATE CASCADE
) COMMENT 'The doctors and the kind of visit(s) they offer';

INSERT INTO `hospital`.`visit` (`id`, `doctor`, `visit_type_id`)
VALUES (1, 'Dr. Mario Colline', 1),
       (2, 'Dr.ssa Angelina Gioielli', 2),
       (3, 'Dr. Franco Franchi', 3),
       (4, 'Dr.ssa Lorenza Esposito', 4),
       (5, 'Dr. Amedeo Ferruccio', 5),
       (6, 'Dr.ssa Francesca Herrera', 6),
       (7, 'Dr. Luca Scanzi', 7);


-- -----------------------------------------------------------------------------
-- Create `appointment` table
-- -----------------------------------------------------------------------------

CREATE TABLE `hospital`.`appointment`
(
    `id`        int AUTO_INCREMENT
        PRIMARY KEY,
    `client_id` int      NOT NULL,
    `visit_id`  int      NOT NULL,
    `time`      datetime NOT NULL,
    CONSTRAINT `appointment_client_id_time_u_index`
        UNIQUE (`client_id`, `time`),
    CONSTRAINT `appointment_visit_id_time_u_index`
        UNIQUE (`visit_id`, `time`),
    CONSTRAINT `appointment_client_id_fk`
        FOREIGN KEY (`client_id`) REFERENCES `hospital`.`client` (`id`)
            ON UPDATE CASCADE,
    CONSTRAINT `appointment_visit_id_fk`
        FOREIGN KEY (`visit_id`) REFERENCES `hospital`.`visit` (`id`)
            ON UPDATE CASCADE
) COMMENT 'The appointments of the clients';

INSERT INTO `hospital`.`appointment` (`id`, `client_id`, `visit_id`, `time`)
VALUES (1, 1, 1, '2027-09-18 16:00:00.0'),
       (2, 2, 2, '2024-11-10 08:30:00.0'),
       (3, 3, 3, '2023-03-02 11:45:00.0');
