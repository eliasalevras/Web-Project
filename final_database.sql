-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 17, 2025 at 08:07 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `web2425`
--

-- --------------------------------------------------------


--CREATE TABLES--

CREATE TABLE student(
Student_number INT(11) PRIMARY KEY NOT NULL,
Student_name VARCHAR(100) ,
Student_surname VARCHAR(100),
Student_street VARCHAR(255),
Student_street_number INT(11),
Student_city VARCHAR(100),
Student_postcode VARCHAR(20),
Student_father_name VARCHAR(100),
Student_landline VARCHAR(20),
Student_mobile VARCHAR(20),
Student_email VARCHAR(255),
Student_User_ID INT(11) UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE professor(
Professor_email VARCHAR(255) PRIMARY KEY NOT NULL,
Professor_name VARCHAR(100),
Professor_surname VARCHAR(100),
Professor_topic VARCHAR(255),
Professor_landline VARCHAR(50),
Professor_mobile VARCHAR(20),
Professor_department VARCHAR(100),
Professor_university VARCHAR(100),
Professor_User_ID INT(11) UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE secretary (
Secretary_User_ID INT(11) PRIMARY KEY NOT NULL,
Secretary_name VARCHAR(100),
Secretary_surname VARCHAR(100)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE user_info(
User_ID INT(11) PRIMARY KEY,
User_Username VARCHAR(255) UNIQUE NOT NULL,
User_Password VARCHAR(255) NOT NULL,
User_Role ENUM('student','secretary','professor')
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE thesis(
Thesis_ID INT(11) PRIMARY KEY NOT NULL,
Thesis_Title VARCHAR(255),
Thesis_Description VARCHAR(255),
Thesis_PDF VARCHAR(255) NOT NULL,
Thesis_Status ENUM('pending' , 'active', 'ready', 'cancel','under_review') DEFAULT NULL,
Thesis_Epimelitis INT(11),
Thesis_Student INT(11),
Thesis_Final_Grade DECIMAL(4,2),
Nimertis_link VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



CREATE TABLE trimelis(
Thesis_ID INT(11) PRIMARY KEY NOT NULL,  
Trimelis_Professor_1 INT(11),
Trimelis_Professor_2 INT(11),
Trimelis_Professor_3 INT(11)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



CREATE TABLE trimelous_invitation(
Thesis_ID INT(11) NOT NULL,
Thesis_Student_Number INT(11),
Professor_User_ID INT(11),
Trimelous_Date DATETIME,
Invitation_Status ENUM ('pending','accept','deny','cancel')
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;





CREATE TABLE thesis_cancellation (
Thesis_ID INT(11) NOT NULL,
General_Meeting_Number INT(11),
General_Meeting_Year INT(11),
Cancellation_Reason VARCHAR(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;




CREATE TABLE thesis_date (
Thesis_ID INT(11),
Thesis_Date DATETIME,
Thesis_Status ENUM('pending','active','ready','cancel','under_review') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;




CREATE TABLE thesis_ap (
Thesis_ID INT(11),
Arithmos_Pistopoiitikou INT(11) 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE draft_thesis(
Thesis_ID INT(11),
draft_thesis_PDF VARCHAR(255),
LINKS VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;




CREATE TABLE presentation_details(
Thesis_ID INT(11),
pres_date DATE,
pres_time TIME, 
pres_type ENUM('online', 'in_person'),
room VARCHAR(50),
pres_link VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



CREATE TABLE grading_criteria (
Thesis_ID INT(11),
Professor_User_ID INT(11) ,
Quality_Goals DECIMAL(4,2),
Time_Interval DECIMAL(4,2),
Text_Quality DECIMAL(4,2),
Presentation DECIMAL(4,2) 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE trimelis_vathmologia (
Thesis_ID INT(11),
Trimelis_Professor_1_Grade DECIMAL(4,2),
Trimelis_Professor_2_Grade DECIMAL(4,2),
Trimelis_Professor_3_Grade DECIMAL(4,2),
Trimelis_Final_Grade DECIMAL(4,2)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE thesis_professor_notes (
Thesis_ID INT(11),
Professor_User_ID INT(11),
Notes VARCHAR(300)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


--FOREIGN KEYS--

ALTER TABLE student 
ADD CONSTRAINT fk_user_student FOREIGN KEY (Student_User_ID) REFERENCES user_info(User_ID)
ON DELETE CASCADE
ON UPDATE CASCADE;

ALTER TABLE professor
ADD CONSTRAINT fk_user_professor FOREIGN KEY (Professor_User_ID) REFERENCES user_info(User_ID)
ON DELETE CASCADE
ON UPDATE CASCADE;

ALTER TABLE secretary 
ADD CONSTRAINT fk_user_secretary FOREIGN KEY (Secretary_User_ID) REFERENCES user_info(User_ID)
ON DELETE CASCADE
ON UPDATE CASCADE;

ALTER TABLE thesis
ADD CONSTRAINT fk_thesis_professor FOREIGN KEY (Thesis_Epimelitis) REFERENCES professor(Professor_User_ID)
ON DELETE CASCADE
ON UPDATE CASCADE;

ALTER TABLE thesis
ADD CONSTRAINT fk_thesis_student FOREIGN KEY (Thesis_Student) REFERENCES student(Student_number)
ON DELETE CASCADE
ON UPDATE CASCADE;

ALTER TABLE thesis_date
ADD CONSTRAINT fk_thesis_date FOREIGN KEY (Thesis_ID) REFERENCES thesis(Thesis_ID)
ON DELETE CASCADE
ON UPDATE CASCADE;

ALTER TABLE trimelis
ADD CONSTRAINT fk_thesis_trimelis FOREIGN KEY (Thesis_ID) REFERENCES thesis(Thesis_ID) 
ON DELETE CASCADE 
ON UPDATE CASCADE;


ALTER TABLE trimelous_invitation
ADD CONSTRAINT fk_trimelous_thesis FOREIGN KEY (Thesis_ID) REFERENCES thesis(Thesis_ID)
ON DELETE CASCADE
ON UPDATE CASCADE;

ALTER TABLE thesis_cancellation
ADD CONSTRAINT fk_thesis_cancellation FOREIGN KEY (Thesis_ID) REFERENCES thesis(Thesis_ID)
ON DELETE CASCADE
ON UPDATE CASCADE;


ALTER TABLE thesis_ap
ADD CONSTRAINT fk_thesis_ap FOREIGN KEY (Thesis_ID) REFERENCES thesis(Thesis_ID)
ON DELETE CASCADE
ON UPDATE CASCADE;

ALTER TABLE draft_thesis
ADD CONSTRAINT fk_draft_thesis FOREIGN KEY (Thesis_ID) REFERENCES thesis(Thesis_ID)
ON DELETE CASCADE
ON UPDATE CASCADE;

ALTER TABLE presentation_details
ADD CONSTRAINT fk_presentation_details FOREIGN KEY (Thesis_ID) REFERENCES thesis(Thesis_ID)
ON DELETE CASCADE
ON UPDATE CASCADE;

ALTER TABLE grading_criteria
ADD CONSTRAINT fk_grading_criteria FOREIGN KEY (Thesis_ID) REFERENCES thesis(Thesis_ID)
ON DELETE CASCADE
ON UPDATE CASCADE;

ALTER TABLE thesis_professor_notes
ADD CONSTRAINT fk_thesis_professor_notes FOREIGN KEY (Thesis_ID) REFERENCES thesis(Thesis_ID)
ON DELETE CASCADE
ON UPDATE CASCADE;

ALTER TABLE trimelis_vathmologia
ADD CONSTRAINT fk_trimelis_vathmologia FOREIGN KEY (Thesis_ID) REFERENCES thesis(Thesis_ID)
ON DELETE CASCADE
ON UPDATE CASCADE;

--CREATE INDEXES--
CREATE INDEX student_index ON student(Student_name, Student_surname);

CREATE INDEX professor_index ON professor(Professor_name, Professor_surname);

CREATE INDEX secretary_index ON secretary(Secretary_name, Secretary_surname);

CREATE INDEX user_info_index ON user_info(User_Username);

CREATE INDEX thesis_index ON thesis(Thesis_Status);

CREATE INDEX trimelis_index ON trimelis(Trimelis_Professor_1,Trimelis_Professor_2,Trimelis_Professor_3);



--INSERT INTO--
INSERT INTO user_info (User_ID, User_Username, User_Password, User_Role) VALUES
(115, 'charis', 'piss', 'secretary'),
(457, '104333999@students.upatras.gr', '1', 'student'),
(458, 'st10434000@upnet.gr', '1', 'student'),
(459, 'st10434001@upnet.gr', '1', 'student'),
(460, 'st10434002@upnet.gr', '1', 'student'),
(461, 'st10434003@upnet.gr', '1', 'student'),
(462, 'st10434004@upnet.gr', '1', 'student'),
(463, 'st10434005@upnet.gr', '1', 'student'),
(464, 'st10434006@upnet.gr', '1', 'student'),
(465, 'st10434007@upnet.gr', '1', 'student'),
(466, 'st10434008@upnet.gr', '1', 'student'),
(467, 'st10434009@upnet.gr', '1', 'student'),
(468, 'st10434010@upnet.gr', '1', 'student'),
(469, 'st10434011@upnet.gr', '1', 'student'),
(470, 'st10434012@upnet.gr', '1', 'student'),
(471, 'st10434013@upnet.gr', '1', 'student'),
(472, 'st10434014@upnet.gr', '1', 'student'),
(473, 'st10434015@upnet.gr', '1', 'student'),
(474, 'st10434016@upnet.gr', '1', 'student'),
(475, 'st10434017@upnet.gr', '1', 'student'),
(476, 'st10434018@upnet.gr', '1', 'student'),
(477, 'st10434019@upnet.gr', '1', 'student'),
(478, 'st10434020@upnet.gr', '1', 'student'),
(479, 'st10434021@upnet.gr', '1', 'student'),
(480, 'st10434022@upnet.gr', '1', 'student'),
(481, 'st10434023@upnet.gr', '1', 'student'),
(482, 'st10434024@upnet.gr', '1', 'student'),
(483, 'st10434025@upnet.gr', '1', 'student'),
(484, 'st10434026@upnet.gr', '1', 'student'),
(485, 'st10434027@upnet.gr', '1', 'student'),
(486, 'st10434028@upnet.gr', '1', 'student'),
(487, 'st10434029@upnet.gr', '1', 'student'),
(488, 'st10434030@upnet.gr', '1', 'student'),
(489, 'st10434031@upnet.gr', '1', 'student'),
(490, 'st10434032@upnet.gr', '1', 'student'),
(491, 'st10434033@upnet.gr', '1', 'student'),
(492, 'st10434034@upnet.gr', '1', 'student'),
(493, 'st10434035@upnet.gr', '1', 'student'),
(494, 'st10434036@upnet.gr', '1', 'student'),
(495, 'st10434037@upnet.gr', '1', 'student'),
(496, 'st10434038@upnet.gr', '1', 'student'),
(497, 'st10434039@upnet.gr', '1', 'student'),
(498, 'st10434040@upnet.gr', '1', 'student'),
(499, 'st10434041@upnet.gr', '1', 'student'),
(500, 'st10434042@upnet.gr', '1', 'student'),
(501, 'st10434043@upnet.gr', '1', 'student'),
(502, 'st10434044@upnet.gr', '1', 'student'),
(503, 'st10434045@upnet.gr', '1', 'student'),
(504, 'st10434046@upnet.gr', '1', 'student'),
(505, 'st10434047@upnet.gr', '1', 'student'),
(506, 'st10434048@upnet.gr', '1', 'student'),
(507, 'st10434049@upnet.gr', '1', 'student'),
(508, 'st10434050@upnet.gr', '1', 'student'),
(509, 'st10434051@upnet.gr', '1', 'student'),
(510, 'st10434052@upnet.gr', '1', 'student'),
(511, 'st10434053@upnet.gr', '1', 'student'),
(512, 'st10434054@upnet.gr', '1', 'student'),
(513, 'st10434055@upnet.gr', '1', 'student'),
(514, 'st10434056@upnet.gr', '1', 'student'),
(515, 'st10434057@upnet.gr', '1', 'student'),
(516, 'st10434058@upnet.gr', '1', 'student'),
(517, 'st10434059@upnet.gr', '1', 'student'),
(518, 'st10434060@upnet.gr', '1', 'student'),
(519, 'st10434061@upnet.gr', '1', 'student'),
(520, 'st10434062@upnet.gr', '1', 'student'),
(521, 'st10434063@upnet.gr', '1', 'student'),
(522, 'st10434064@upnet.gr', '1', 'student'),
(523, 'st10434065@upnet.gr', '1', 'student'),
(524, 'st10434066@upnet.gr', '1', 'student'),
(525, 'st10434067@upnet.gr', '1', 'student'),
(526, 'st10434068@upnet.gr', '1', 'student'),
(527, 'st10434069@upnet.gr', '1', 'student'),
(528, 'st10434070@upnet.gr', '1', 'student'),
(529, 'st10434071@upnet.gr', '1', 'student'),
(530, 'st10434072@upnet.gr', '1', 'student'),
(531, 'st10434073@upnet.gr', '1', 'student'),
(532, 'st10434074@upnet.gr', '1', 'student'),
(533, 'st10434075@upnet.gr', '1', 'student'),
(534, 'st10434076@upnet.gr', '1', 'student'),
(535, 'akomninos@ceid.upatras.gr', '0', 'professor'),
(536, 'vasfou@ceid.upatras.gr', '0', 'professor'),
(537, 'karras@nterti.com', '0', 'professor'),
(538, 'eleni@ceid.gr', '0', 'professor'),
(539, 'hozier@ceid.upatras.gr', '0', 'professor'),
(540, 'nikos.korobos12@gmail.com', '0', 'professor'),
(541, 'kostkaranik@gmail.com', '0', 'professor'),
(542, 'mpampis123@gmail.com', '0', 'professor'),
(543, 'makavelibet@gmail.com', '0', 'professor'),
(544, 'palam@upatras.gr', '0', 'professor'),
(545, 'meniT@upatras.gr', '0', 'professor'),
(546, 'tzouli.ax@upatras.gr', '0', 'professor'),
(547, 'karikhs@yahoo.gr', '0', 'professor'),
(548, 'toxrusoftiari@funerals.gr', '0', 'professor'),
(549, 'fatbanker@kapitalas.gr', '0', 'professor'),
(550, 'info@hamzat.gr', '0', 'professor'),
(551, 'snikolaou@upatras.gr', '0', 'professor'),
(552, 'pdanezis@upatras.gr', '0', 'professor'),
(553, 'eustratiospap@gmail.com', '0', 'professor'),
(554, 'mariakon@gmail.com', '0', 'professor'),
(555, 'jimnik@gmail.com', '0', 'professor'),
(556, 'sophiamich@gmail.com', '0', 'professor'),
(557, 'michaelpap@gmail.com', '0', 'professor'),
(558, 'elonmusk@gmail.com', '0', 'professor'),
(559, 'abcdef@example.com', '0', 'professor'),
(560, 'abcdefg@example.com', '0', 'professor'),
(561, 'exxample@example.com', '0', 'professor'),
(562, 'patric@xrusopsaros.com', '0', 'professor'),
(563, 'paraskevas@kobres.ath', '0', 'professor'),
(564, 'masterassassin@upatras.ceid.gr', '0', 'professor'),
(565, 'spana@hotmail.com', '0', 'professor'),
(566, 'anittamaxwynn@cashmoney.com', '0', 'professor'),
(567, 'goatmanager@thrylos.gr', '0', 'professor'),
(568, 'liampayne@ceid.upatras.gr', '0', 'professor'),
(569, 'zaynmalik@gmail.com', '0', 'professor'),
(570, 'st10434077@upnet.gr', '1', 'student'),
(571, 'st10434078@upnet.gr', '1', 'student'),
(572, 'st10434079@upnet.gr', '1', 'student'),
(573, 'st10434080@upnet.gr', '1', 'student'),
(574, 'st10434081@upnet.gr', '1', 'student'),
(575, 'st10434082@upnet.gr', '1', 'student'),
(576, 'st10434083@upnet.gr', '1', 'student'),
(577, 'st10434084@upnet.gr', '1', 'student'),
(578, 'st10434085@upnet.gr', '1', 'student'),
(579, 'st10434086@upnet.gr', '1', 'student'),
(580, 'st10434087@upnet.gr', '1', 'student'),
(581, 'st10434088@upnet.gr', '1', 'student'),
(582, 'st10434089@upnet.gr', '1', 'student'),
(583, 'st10434090@upnet.gr', '1', 'student'),
(584, 'st10434091@upnet.gr', '1', 'student'),
(585, 'st10434092@upnet.gr', '1', 'student'),
(586, 'st10434093@upnet.gr', '1', 'student'),
(587, 'st10434094@upnet.gr', '1', 'student'),
(588, 'st10434095@upnet.gr', '1', 'student'),
(589, 'st10434096@upnet.gr', '1', 'student'),
(590, 'papas2@yahoo.gr', '0', 'professor'),
(591, 'mavros@bbs.af', '0', 'professor'),
(592, 'ihatepotter@hocusmail.com', '0', 'professor'),
(593, 'tungtungtung@itbr.com', '0', 'professor'),
(594, 'up1084561@ac.upatras.gr', '0', 'professor'),
(595, 'up1234567@ac.upatras.gr', '0', 'professor'),
(596, 'mari-bro@beast.com', '0', 'professor'),
(597, 'goat@messi.cr', '0', 'professor'),
(598, 'capucapu@ccino.assassino', '0', 'professor'),
(599, 'johnusins@upatras.gr', '0', 'professor'),
(600, 'georgeNofragka@utsipis.gr', '0', 'professor'),
(601, 'tsilis@tsilliuniversity.gr', '0', 'professor'),
(602, 'prasinosfrouros@gmail.com', '0', 'professor'),
(603, 'Gbonassera@gmail.com', '0', 'professor'),
(604, 'GBar@gmail.com', '0', 'professor');


INSERT INTO professor (Professor_email, Professor_name, Professor_surname, Professor_topic, Professor_landline, Professor_mobile, Professor_department, Professor_university, Professor_User_ID) VALUES
('abcdef@example.com', 'Kostas', 'Kalantas', 'AI', '2610121212', '6912121212', 'department', 'University', 559),
('abcdefg@example.com', 'Giorgis', 'Fousekis', 'topic', 'land', 'mob', 'dep', 'university', 560),
('akomninos@ceid.upatras.gr', 'Andreas', 'Komninos', 'Network-centric systems', '2610996915', '6977998877', 'CEID', 'University of Patras', 535),
('anittamaxwynn@cashmoney.com', 'Anitta', 'Wynn', 'Probability', '2610486396', '698888884', 'Computer Engineering', 'University of Beegwean', 566),
('capucapu@ccino.assassino', 'Brain', 'Rot', 'no', '9', '6', 'Brainrot', '<a href=\"https://www.youtube.com/watch?v=nxSbhVnwdFw&t=1121s\">Crocodilo</a>', 598),
('eleni@ceid.gr', 'Eleni', 'Voyiatzaki', 'WEB', '34', '245', 'CEID', 'University of Patras', 538),
('elonmusk@gmail.com', 'Elon', 'Musk', 'Electric Vehicles', '1-888-518-3752', 'Null', 'Department of Physics', 'University of Pennsylvania, Philadelphia', 558),
('eustratiospap@gmail.com', 'Papadopoulos ', 'Eustathios', 'Physics', '210-1234567', '690-1234567', 'Physics', 'National and Kapodistrian University of Athens', 553),
('exxample@example.com', 'Nikos', 'Koukos', 'top', 'la', 'mo', 'de', 'university', 561),
('fatbanker@kapitalas.gr', 'Fat ', 'Banker', 'kippah', '6942014121', '6969784205', 'Froutemporiki', 'University of Israel', 549),
('GBar@gmail.com', 'Giorgos', 'Bartzokas', 'Basketball Strategy', '2108743265', '6932178542', 'SEF', 'University of Gate 7', 604),
('Gbonassera@gmail.com', 'Giorgio ', 'Bonassera', 'spagetti aldente', '23131131', '6575754', 'cuccina italiana', 'Carbonara University', 603),
('georgeNofragka@utsipis.gr', 'Giorgos', 'Fragkofonias', 'oikonomia tou tsipi', '2610546132', '697878787', 'Real Economics ', 'University Of Empty Pocket', 600),
('goat@messi.cr', '<a href=\"https://www.youtube.com\">G</a>', 'Goat', 'no', '666', '666', 'No', 'University of Goats', 597),
('goatmanager@thrylos.gr', 'Jose Luis', 'Mendilibar', 'Sentres', '2105555555', '6922222222', 'Conference League', 'Uni of Olympiacos', 567),
('hozier@ceid.upatras.gr', 'Andrew', 'Hozier Byrne', 'Artificial Intelligence', '2610170390', '6917031990', 'CEID', 'University of Patras', 539),
('ihatepotter@hocusmail.com', 'Severus', 'Snape', 'math 2', '26210 26441', '6926626226', 'ceid', 'University of Patras', 592),
('info@hamzat.gr', 'Hamze', 'Mohamed', 'Logistics', '1245789513', '1456983270', 'Social Rehabitation', 'University of UAE', 550),
('jimnik@gmail.com', 'Jim', 'Nikolaou', 'Artificial Intelligence', '2610-9876543', '697-9876543', 'Computer Science', 'University of Patras', 555),
('johnusins@upatras.gr', 'Giannis ', 'Sinsidis', 'Ypsiloikardiakoipalmoi', '2610645698', '697878787', 'Palindromikis kiniseos', 'University of Makias', 599),
('karikhs@yahoo.gr', 'Karikhs', 'Raftel', 'Pharmaceutical Drugs', '69', '6945258923', 'Chemistry', 'University of Streets', 547),
('karras@nterti.com', 'Basilis', 'Karras', 'Artificial Intelligence', '23', '545', 'CEID', 'University of Patras', 537),
('kostkaranik@gmail.com', 'Kostas', 'Karanikolos', 'informatics', '2610324242', '6934539920', 'CEID', 'University of Patras', 541),
('liampayne@ceid.upatras.gr', 'Liam', 'Payne', 'Cryptography', '2462311345', '6980847234', 'CEID', 'University of Patras', 568),
('makavelibet@gmail.com', 'Daskalos', 'Makaveli', 'Business', '2310231023', '6929349285', 'Economics', 'UOA', 543),
('mari-bro@beast.com', 'MARI', 'BRO', 'Life', '666', '666', 'no', 'University of Brain', 596),
('mariakon@gmail.com', 'Konstantinou', 'Maria', 'Statistics and Probability', '2310-7654321', '694-7654321', 'Mathematics', 'Aristotle University of Thessaloniki', 554),
('masterassassin@upatras.ceid.gr', 'Ezio', 'Auditore da Firenze', 'assassinations', 'null', 'null', 'Monterigioni', 'University of Assasinos', 564),
('mavros@bbs.af', 'Oikoumenikos', 'Prasinos', 'Nikolakos', '987546123', '69 6 9 69', 'Ougantiani Filosofia', 'Nation University Of Pakistan', 591),
('meniT@upatras.gr', 'Meni', 'Talaiporimeni', 't', '2610333999', '6999990999', 'CEID', 'UoP', 545),
('michaelpap@gmail.com', 'Michael ', 'Papadreou', 'Renewable Energy Systems', '2610-4455667', '697-4455667', 'Electrical Engineering', 'University of Ioannina', 557),
('mpampis123@gmail.com', 'Mpampis', 'Sougias', 'Arxeologia', '2610945934', '6947845334', 'Arxeologias', 'UOI', 542),
('nikos.korobos12@gmail.com', 'Nikos', 'Korobos', 'Data Engineering', '2610324365', '6978530352', 'IT', 'University of Patras', 540),
('palam@upatras.gr', 'Maria', 'Palami', 'SQL injections', '1234567890', '6988223322', 'Engineering', 'University of SKG', 544),
('papas2@yahoo.gr', 'Nikos ', 'Papas', 'manas', 'spiti', '69854512', 'Hastle', 'Hastle University', 590),
('paraskevas@kobres.ath', 'Paraskevas', 'koutsikos', 'Provata', '2298042035', '6969696969', 'Ktinotrofia', 'University of Methana', 563),
('patric@xrusopsaros.com', 'patrick', 'xrusopsaros', 'thalasioi ipopotamoi', '2610567917', '6952852742', 'Solomos', 'Nemo', 562),
('pdanezis@upatras.gr', 'Petros', 'Danezis', 'Telecommunication Electronics', '2610908888', '6971142424', 'ECE', 'University of Patras	', 552),
('prasinosfrouros@gmail.com', 'Prasinos ', 'Frouros', 'alafouzo poula', '261056458', '698778788', 'Panathinaiki agwgh', 'University of tears', 602),
('snikolaou@upatras.gr', 'Stefania', 'Nikolaou', 'Information Theory', '2106723456', '6942323452', 'ECE', 'University of Patras', 551),
('sophiamich@gmail.com', 'Sophia', 'Michailidi', 'Economic Theory', '2310-5432109', '698-5432109', 'Economics', 'Athens University of Economics and Business', 556),
('spana@hotmail.com', 'Sotiris', 'Panaikas', 'Bet Predictions', '1235654899', '2310521010', 'opap', 'London', 565),
('toxrusoftiari@funerals.gr', 'Vlasis', 'Restas', 'Nekro8aftiki', '78696910', '69696964', 'Nekro8aftikis', 'University Of Ohio', 548),
('tsilis@tsilliuniversity.gr', 'Ioannis', 'Tsilis', 'Iliopoulos to fainomeno', '2610212121', '6921212121', 'Tsili Kafeneio', 'University of the Road', 601),
('tungtungtung@itbr.com', 'Tung Tung', 'Sahur', 'Graphs', '210 1425735', '69434619363', 'CEID', 'University of Patras', 593),
('tzouli.ax@upatras.gr', 'Tzouli', 'Alexandratou', 'Big Data', '2264587412', '6996116921', 'CEID', 'University of Patras', 546),
('up1084561@ac.upatras.gr', 'Maria', 'Papadopoulou', 'Computer science', '2610123456', '6912345678', 'CEID', 'University of Patras', 594),
('up1234567@ac.upatras.gr', 'Nikos', 'Georgiou', 'Physics', '2610111111', '6911111111', 'CEID', 'University of Patras', 595),
('vasfou@ceid.upatras.gr', 'Vasilis', 'Foukaras', 'Integrated Systems', '2610885511', '6988812345', 'CEID', 'University of Patras', 536),
('zaynmalik@gmail.com', 'Zayn', 'Malik', 'Oriented programing', '2310221234', '6971006355', 'CEID', 'University of Patras', 569);



INSERT INTO student (Student_number, Student_name, Student_surname, Student_street, Student_street_number, Student_city, Student_postcode, Student_father_name, Student_landline, Student_mobile, Student_email, Student_User_ID) VALUES
(10433999, 'Makis', 'Makopoulos', 'test street', 45, 'test city', '39955', 'Orestis', '2610333000', '6939096979', '104333999@students.upatras.gr', 457),
(10434000, 'John', 'Lennon', 'Ermou', 18, 'Athens', '10431', 'George', '2610123456', '6970001112', 'st10434000@upnet.gr', 458),
(10434001, 'Petros', 'Verikokos', 'Adrianou', 20, 'Thessaloniki', '54248', 'Giannis', '2610778899', '6970001112', 'st10434001@upnet.gr', 459),
(10434002, 'test', 'name', 'str', 1, 'patra', '26222', 'father', '2610123456', '6912345678', 'st10434002@upnet.gr', 460),
(10434003, 'Robert', 'Smith', 'Fascination', 17, 'London', '1989', 'Alex', '2610251989', '6902051989', 'st10434003@upnet.gr', 461),
(10434004, 'Rex', 'Tyrannosaurus', 'Cretaceous', 2, 'Laramidia', '54321', 'Daspletosaurus', '2610432121', '6911231234', 'st10434004@upnet.gr', 462),
(10434005, 'Paul', 'Mescal ', 'Smith Str.', 33, 'New York ', '59', 'Paul', '-', '-', 'st10434005@upnet.gr', 463),
(10434006, 'Pedro', 'Pascal', 'Johnson', 90, 'New York ', '70', 'José ', '-', '-', 'st10434006@upnet.gr', 464),
(10434007, 'David', 'Gilmour', 'Sortef', 29, 'New York', '26', 'Douglas', '-', '-', 'st10434007@upnet.gr', 465),
(10434008, 'Lana', 'Del Rey ', 'Groove Str.', 23, 'Los Angeles', '1', 'none', '-', '-', 'st10434008@upnet.gr', 466),
(10434009, 'Stevie', 'Nicks', 'Magic Str. ', 8, 'New Orleans', '35', 'Jess ', '56', '67', 'st10434009@upnet.gr', 467),
(10434010, 'Margaret', 'Qualley', 'Substance Str.', 25, 'Los Angeles ', '7', 'Paul', '67', '90', 'st10434010@upnet.gr', 468),
(10434011, 'Mia', 'Goth', 'Pearl Str. ', 4, 'Michigan', '8', 'Lee', '-', '-', 'st10434011@upnet.gr', 469),
(10434012, 'Florence ', 'Pugh', 'Midsommar Str. l', 1, 'Away', '24', '-', '5', '2', 'st10434012@upnet.gr', 470),
(10434013, 'PJ ', 'Harvey', 'Lonely Str.', 27, 'Bridport', '-7', 'Ray', '56', '43', 'st10434013@upnet.gr', 471),
(10434014, 'Penélope', 'Cruz', 'Almadovar', 55, 'Madrid', '23', 'Eduardo ', '5', '4', 'st10434014@upnet.gr', 472),
(10434015, 'Emma', 'Stone', 'Poor Str.', 3, 'Paris ', '34', 'none', '2333333', '4455555', 'st10434015@upnet.gr', 473),
(10434016, 'Jenny', 'Vanou', 'Mpouat Str.', 23, 'Athens', '10', 'Basil', '09', '45', 'st10434016@upnet.gr', 474),
(10434017, 'Salma ', 'Hayek', 'Desperado Str. ', 24, 'Madrid ', '656', 'Sami', '344', '221', 'st10434017@upnet.gr', 475),
(10434018, 'Julie ', 'Delpy', 'Before Str.', 36, 'Paris', '567', 'Kieślowski', '1223', '3455', 'st10434018@upnet.gr', 476),
(10434019, 'Giannis', 'Aggelakas', 'Trypes Str.', 3, 'Athens', '2354', 'Theos', '23', '45', 'st10434019@upnet.gr', 477),
(10434020, 'Eleutheria ', 'Arvanitaki', 'Entexno Str. ', 2, 'Athens', '345', 'Kosmos', '657', '345', 'st10434020@upnet.gr', 478),
(10434021, 'Marina', 'Spanou', 'Pagkrati Str.', 25, 'Athens', '2456', 'Gates', '897', '354', 'st10434021@upnet.gr', 479),
(10434022, 'Rena', 'Koumioti', 'Mpouat Str.', 24, 'Athens', '5749', 'Ellhniko', '23557', '32453', 'st10434022@upnet.gr', 480),
(10434023, 'Charlotte', 'Aitchison', 'Boiler Room St', 365, 'New York', '360', 'Jon', '2610365365', '693653365', 'st10434023@upnet.gr', 481),
(10434024, 'Rhaenyra', 'Targaryen', 'Dragon St', 2021, 'Kings Landing', '2021', 'Viserys', '2610101010', '6910101010', 'st10434024@upnet.gr', 482),
(10434025, 'Ben', 'Dover', 'Colon Str.', 124, 'NY', '11045', 'Carlos', '2584694587', '5841852384', 'st10434025@upnet.gr', 483),
(10434026, 'Marios', 'Papadakis', 'Korinthou', 266, 'Patras', '26223', 'Ioannis', '+302105562567', '+306975562567', 'st10434026@upnet.gr', 484),
(10434027, 'Nicholas ', 'Hoult', 'Nosferatu Str.', 34, 'London', '567', 'Roger', '436', '46478', 'st10434027@upnet.gr', 485),
(10434028, 'Joo Hyuk', 'Nam', 'Kanakari', 135, 'Patra', '26440', 'Baek Yi Jin', '2610443568', '6978756432', 'st10434028@upnet.gr', 486),
(10434029, 'Nikos', 'Peletie', 'Kolokotroni', 6, 'Athens', '34754', 'George', '2104593844', '6987655433', 'st10434029@upnet.gr', 487),
(10434030, 'Nikos', 'Koukos', 'Triton', 12, 'Salamina', '12216', 'Giannis', '210553985', '6946901012', 'st10434030@upnet.gr', 488),
(10434031, 'Maria', 'Fouseki', 'Jason ', 33, 'London', '44391', 'Tasos', '2109993719', '6923144642', 'st10434031@upnet.gr', 489),
(10434032, 'Nikos ', 'Korobos', 'Masalias', 4, 'Sparti', '32095', 'Giannis', '2279036758', '6948308576', 'st10434032@upnet.gr', 490),
(10434033, 'Maria', 'Togia', 'Athinon', 4, 'Athens', '28482', 'Petros', '2100393022', '6953782102', 'st10434033@upnet.gr', 491),
(10434034, 'Giorgos', 'Menegakis', 'korinthou', 56, 'patras', '56892', 'nikos', '2610485796', '6934527125', 'st10434034@upnet.gr', 492),
(10434035, 'Trakis', 'Giannakopoulos', 'Othonos kai Amalias ', 100, 'Patras', '26500', 'None', '2610381393', '6028371830', 'st10434035@upnet.gr', 493),
(10434036, 'Chris', 'Kouvadis', 'vanizelou', 36, 'Patras', '26500', 'Pfloutsou', '2610995999', '6947937524', 'st10434036@upnet.gr', 494),
(10434037, 'pafloutsou', 'kaskarai', 'kolokotroni', 12, 'Patras', '26500', 'mauragkas', '2610978423', '6935729345', 'st10434037@upnet.gr', 495),
(10434038, 'Billy', 'Diesel', 'Alexandras Ave', 12, 'Athens', '11521', 'Iman', '2101234567', '6912345678', 'st10434038@upnet.gr', 496),
(10434039, 'Tome', 'of Madness', 'Panepisthmiou', 69, 'Patras', '26441', 'Prafit', '2610654321', '6969966996', 'st10434039@upnet.gr', 497),
(10434040, 'fort', 'nite', 'karaiskakis', 69, 'tilted tower', '4747', 'epic games', '2610747474', '6988112233', 'st10434040@upnet.gr', 498),
(10434041, 'Zeus', 'Ikosaleptos', 'Novi', 25, 'Athens', '20033', 'Kleft', '2109090901', '6900008005', 'st10434041@upnet.gr', 499),
(10434042, 'AG', 'Cook', 'Britpop', 7, 'London', '2021', 'PC Music', '2121212121', '1212121212', 'st10434042@upnet.gr', 500),
(10434043, 'Maria', 'Mahmood', 'Mouratidi', 4, 'New York', '25486', 'Paparizou', '2108452666', '6980081351', 'st10434043@upnet.gr', 501),
(10434044, 'Kostas', 'Poupis', 'Ag Kiriakis', 11, 'Papaou', '50501', 'Aelakis', '222609123', '698452154', 'st10434044@upnet.gr', 502),
(10434045, 'Hugh', 'Jass', 'Wall Street', 69, 'Jerusalem', '478', 'Mike Oxlong', '69696969', '696969420', 'st10434045@upnet.gr', 503),
(10434046, 'Xontro ', 'Pigouinaki', 'Krasopotirou', 69, 'Colarato', '14121', 'Adolf Heisenberg', '6913124205', '4747859625', 'st10434046@upnet.gr', 504),
(10434047, 'Μaria', 'Nikolaou', 'Achilleos', 21, 'Athens', '10437', 'Dimitris', '2109278907', '6945533213', 'st10434047@upnet.gr', 505),
(10434048, 'Eleni', 'Fotiou', 'Adrianou ', 65, 'Athens', '10556', 'Nikos', '2108745645', '6978989000', 'st10434048@upnet.gr', 506),
(10434049, 'Xara', 'Fanouriou', 'Chaonias ', 54, 'Athens', '10441', 'Petros', '2108724324', '6945622222', 'st10434049@upnet.gr', 507),
(10434050, 'Nikos', 'Panagiotou', 'Chomatianou', 32, 'Athens', '10439', 'Giorgos', '2107655555', '6941133333', 'st10434050@upnet.gr', 508),
(10434051, 'Petros', 'Daidalos', 'Dafnidos', 4, 'Athens', '11364', 'Pavlos', '2108534566', '6976644333', 'st10434051@upnet.gr', 509),
(10434052, 'Giannis', 'Ioannou', 'Danais', 9, 'Athens', '11631', 'Kostas', '2107644999', '6976565655', 'st10434052@upnet.gr', 510),
(10434053, 'Tsili', 'Doghouse', 'novi lane', 33, 'Patras', '26478', 'Stoiximan', '2610420420', '6999999999', 'st10434053@upnet.gr', 511),
(10434054, 'Marialena', 'Antoniou', 'Ermou', 24, 'Athens', '10563', 'Nikolaos', '210-5678901', '693-5678901', 'st10434054@upnet.gr', 512),
(10434055, 'Ioannis', 'Panagiotou', 'Kyprou', 42, 'Patra', '26441', 'Kwstas', '2610-123456', '698-1234567', 'st10434055@upnet.gr', 513),
(10434056, 'George', 'Karamalis', 'Kolokotroni', 10, 'Larissa', '41222', 'Petros', '2410-456789', '697-4567890', 'st10434056@upnet.gr', 514),
(10434057, 'Kyriakos', 'Papapetrou', 'Zakunthou', 36, 'Volos', '10654', 'Apostolos', '210-6789012', '695-6789012', 'st10434057@upnet.gr', 515),
(10434058, 'Maria', 'Kp', 'pelopidas ', 52, 'patra', '28746', 'george', '2610555555', '6932323232', 'st10434058@upnet.gr', 516),
(10434059, 'Nikos', 'papadopoulos', 'anapafseos', 34, 'patra', '26503', 'takis', '2691045092', '69090909', 'st10434059@upnet.gr', 517),
(10434060, 'Giannis ', 'Molotof', 'Ermou', 34, 'Patras', '29438', 'Giorgos', '2610254390', '6943126767', 'st10434060@upnet.gr', 518),
(10434061, 'Sagdy', 'Znuts', 'Grove', 12, 'San Andreas', '123456', 'NULL', '123456789', '123456789', 'st10434061@upnet.gr', 519),
(10434062, 'Mary', 'Poppins', 'Niktolouloudias ', 123, 'Chalkida', '23456', 'George', '2613456089', '6980987654', 'st10434062@upnet.gr', 520),
(10434063, 'Tinker', 'Bell', 'Vatomourias', 55, 'Pano Raxoula', '2345', 'Mixail', '2456034567', '6987543345', 'st10434063@upnet.gr', 521),
(10434064, 'Lilly', 'Bloom', 'Patnanasis', 45, 'Patra', '26440', 'Menelaos', '2610435988', '6987555433', 'st10434064@upnet.gr', 522),
(10434065, 'GIORGOS', 'MASOURAS', 'AGIOU IOANNNI RENTI', 7, 'PEIRAIAS', '47200', 'PETROS', '694837204', '210583603', 'st10434065@upnet.gr', 523),
(10434066, 'KENDRICK', 'NUNN', 'OAKA', 25, 'ATHENS', '666', 'GIANNAKOPOULOS', '6982736199', '6906443321', 'st10434066@upnet.gr', 524),
(10434067, 'Depeche', 'Mode', 'Enjoy The Silence', 1990, 'London', '1990', 'Dave', '1234567890', '1234567770', 'st10434067@upnet.gr', 525),
(10434068, 'name', 'surname', 'your', 69, 'mom', '15584', 'father', '222', '2223', 'st10434068@upnet.gr', 526),
(10434069, 'Nikos', 'Kosmopoulos', 'Araksou', 12, 'Giotopoli', '69420', 'Greg', '210 9241993', '6978722312', 'st10434069@upnet.gr', 527),
(10434070, 'Aris', 'Poupis', 'Mpofa', 10, 'Kolonia', '12345', 'Mpamias', '2105858858', '6935358553', 'st10434070@upnet.gr', 528),
(10434071, 'gerry', 'banana', 'lootlake', 12, 'tilted', '26500', 'johnesy', '6947830287', '2610987632', 'st10434071@upnet.gr', 529),
(10434072, 'grekotsi', 'parthenios', 'kokmotou', 69, 'thessaloniki', '20972', 'mourlo', '6947910234', '2610810763', 'st10434072@upnet.gr', 530),
(10434073, 'Mochi', 'Mon', 'Novi', 55, 'Maxxwin', '99999', 'Drake', '2610550406', '6967486832', 'st10434073@upnet.gr', 531),
(10434074, 'Nikolaos', 'Serraios', 'Papaflessa', 12, 'Patra', '26222', 'Georgios', '2610456632', '6975849305', 'st10434074@upnet.gr', 532),
(10434075, 'Xaralampos', 'Mparmaksizoglou', 'Konstantinoupoleos', 32, 'Athens', '16524', 'Eugenios', '2109995555', '6912345678', 'st10434075@upnet.gr', 533),
(10434076, 'kyriakos', 'pareena', 'karaiskaki', 23, 'patras', '23444', 'lebron', '2214567809', '6972861212', 'st10434076@upnet.gr', 534),
(10434077, 'Tortelino', 'Diagrafino', 'emp', 69, 'empa', '5432', 'kaae', '2101312000', '6913121312', 'st10434077@upnet.gr', 570),
(10434078, 'Maria', 'Db', 'Spiti sou', 3, 'Patras', '26441', 'sql', '2610 123456', '6912345678', 'st10434078@upnet.gr', 571),
(10434079, 'Bombardriro', 'Crocodilo', 'Pony Peponi', 69, 'Athens', '15344', 'Lirili Larila', '26810 12345', '6909876543', 'st10434079@upnet.gr', 572),
(10434080, 'Balerinna ', 'Cappucinna', 'mimimimi', 4, 'lalalala', '23861', 'balerinno lololo', '2610729878', '6983615882', 'st10434080@upnet.gr', 573),
(10434081, 'Ntinos', 'Konstantinos', 'Valaoritou', 1, 'patras', '26225', 'Nikolaos', '2610222222', '6988888888', 'st10434081@upnet.gr', 574),
(10434082, 'Xara', 'Georgiou', 'Psilalonia', 12, 'Patras', '26225', 'Giorgos', '261000000', '6933333333', 'st10434082@upnet.gr', 575),
(10434083, 'Marios', 'Konstantinou', 'Kanakari', 1, 'Patras', '26225', 'Foivos', '2610777777', '6944444444', 'st10434083@upnet.gr', 576),
(10434084, 'Mina', 'Minopoulou', 'patra', 13, 'patras', '12345', 'makis', '261044444', '699999999', 'st10434084@upnet.gr', 577),
(10434085, 'Sakis', 'Rouvas', 'Raftel', 45, 'Piece', '123', 'Gol', '66666666', '66666666', 'st10434085@upnet.gr', 578),
(10434086, 'Shinji', 'Ikari', '	NERV Boulevard', 4, '	Tokyo-3', '192', 'Gendo Ikari', '	0366666666', '	08012345678', 'st10434086@upnet.gr', 579),
(10434087, 'Alexis', 'tsipras', 'Kilkis', 13, 'patra', '26441', 'Kostaw', '6978215130', '6978215130', 'st10434087@upnet.gr', 580),
(10434088, 'Tasos', 'kolokotronhs', 'alitheias', 69, 'Igoumenitsa', '24463', 'Theodoros', '26578953', '6978584575', 'st10434088@upnet.gr', 581),
(10434089, 'Minas ', 'Minaroglou', 'ksefotou', 36, 'Moon city', '245643', 'Manolis', '465352358', '698713245', 'st10434089@upnet.gr', 582),
(10434090, 'La', 'Polizia', 'Mpatsenou', 46, 'Sideria', '164542', 'Klavdios', '4673596', '55464852', 'st10434090@upnet.gr', 583),
(10434091, 'manousos', 'Dlabiras', 'giannitson', 47, 'Tripoli', '23100', 'Georgios', '23242424', '24242424', 'st10434091@upnet.gr', 584),
(10434092, 'Nick', 'Calathes', 'Ermou', 28, 'Athens', '10551', 'Giorgos', '2105863247', '6945218947', 'st10434092@upnet.gr', 585),
(10434093, 'Donald', 'Trump', 'White House', 911, 'Washington ', '2049', 'Fred', '2024561111', '2024561414', 'st10434093@upnet.gr', 586),
(10434094, 'Pelina', 'Anastasopoulou', 'poseidon', 20, 'patras', '26332', 'andreas', '2610324567', '6949396780', 'st10434094@upnet.gr', 587),
(10434095, 'Andriana', 'Kapogiannopoulou', 'poseidon', 12, 'patras', '26332', 'George', '2610321456', '6949396731', 'st10434095@upnet.gr', 588),
(10434096, 'Rixardos', 'Leodokardos', 'Patissia', 69, 'Athens', '26312', 'Apostolos', '2106969420', '6969696969', 'st10434096@upnet.gr', 589);

INSERT INTO secretary (Secretary_User_ID, Secretary_name, Secretary_surname) VALUES
(115, 'charis', 'piss');

INSERT INTO thesis (Thesis_ID, Thesis_Title, Thesis_Description, Thesis_PDF, Thesis_Status, Thesis_Epimelitis,Thesis_Student, Thesis_Final_Grade, Nimertis_link) VALUES
(9, 'hello', 'wqe', '../uploads/thesis_689b81377eeb67.05482652_--CA.pdf', 'ready', '560', 10434001, '6.20', ''),
(10, '10001', '3', '../uploads/thesis_68a1dfcdb88967.28130055_--CA.pdf', 'ready', '538', 10433999, '9.20', ''),
(11, '321', '3', '../uploads/thesis_68a1e42a67ce69.22843348_--CA.pdf', 'under_review', '538', 10434012, NULL, ''),
(12, '13', '13', '../uploads/thesis_68a354168f2714.54602184_--CA.pdf', 'pending', '538', 10434009, NULL, ''),
(13, 'qwe', 'qwe', '../uploads/thesis_68a357dc7ea1f2.52274554_--CA.pdf', 'pending', '538', 10434023, NULL, ''),
(14, 'wqe', 'qweqw', '../uploads/thesis_68c308362e9605.74237925_--CA.pdf', NULL, '538', NULL, NULL, ''),
(15, 'Δοκιμή 1', 'Thesis with Eleni Voyiatzaki in pos 1', NULL, 'pending', '538', 10434000, NULL, ''),
(16, 'Δοκιμή 2', 'Thesis with Eleni Voyiatzaki in pos 2', NULL, 'pending', '538', 10434001, NULL, ''),
(17, 'Δοκιμή 3', 'Thesis with Eleni Voyiatzaki in pos 3', NULL, 'pending', '538', 10434002, NULL, '');

INSERT INTO draft_thesis (Thesis_ID, draft_thesis_PDF,LINKS) VALUES
(11,"../thesis_draft//thesis_68cb15c236e2e9.60018522.pdf",NULL),
(10,"../thesis_draft//thesis_68cc522d98e4b8.19583043.pdf",NULL),
(9,"../thesis_draft//thesis_68cc5ac80dc1f3.69209587.pdf",NULL),
(12,"../thesis_draft//thesis_68cc5fb48a4530.56515359.pdf",NULL);

INSERT INTO presentation_details (Thesis_ID, pres_date, pres_time,pres_type, room, pres_link) VALUES
(11,"2025-09-17","14:18:00","in_person",1,NULL);

INSERT INTO grading_criteria (Thesis_ID, Professor_User_ID, Quality_Goals, Time_Interval, Text_Quality, Presentation) VALUES
(10, 559, '10.00', '1.00', '1.00', '1.00'),
(10, 559, '10.00', '9.00', '9.00', '10.00'),
(10, 559, '10.00', '10.00', '9.00', '5.00'),
(10, 560, '10.00', '8.00', '8.00', '1.00'),
(10, 560, '10.00', '8.00', '8.00', '1.00'),
(9, 538, '10.00', '3.00', '5.00', '5.00'),
(9, 538, '9.00', '5.00', '5.00', '5.00'),
(9, 538, '10.00', '9.00', '9.00', '9.00'),
(9, 538, '10.00', '5.00', '5.00', '5.00'),
(9, 560, '5.00', '9.00', '1.00', '1.00'),
(9, 538, '9.00', '9.00', '9.00', '9.00'),
(9, 559, '5.00', '5.00', '5.00', '5.00');

INSERT INTO thesis_ap (Thesis_ID, Arithmos_Pistopoiitikou) VALUES
(9, 12);

INSERT INTO thesis_cancellation (Thesis_ID, General_Meeting_Number, General_Meeting_Year, Cancellation_Reason) VALUES
(11, 23, 2025, 'Κατόπιν αίτησης Φοιτητή/τριας.'),
(12, 0, 0, 'Από Διδάσκοντα.');

INSERT INTO thesis_date (Thesis_ID, Thesis_Date, Thesis_Status) VALUES
(11, '2025-08-17 17:19:55', 'active'),
(11, '2025-08-17 19:57:41', 'cancel'),
(11, '2025-08-17 20:07:43', 'cancel'),
(12, '2025-08-18 19:29:57', 'active'),
(12, '2025-09-07 20:21:45', 'cancel'),
(12, '2025-09-07 20:36:29', 'cancel'),
(12, '2025-09-07 21:35:42', 'cancel'),
(12, '2025-09-07 21:37:54', 'under_review'),
(11, '2025-09-08 20:44:26', 'cancel'),
(10, '2025-09-11 17:08:24', 'ready'),
(10, '2025-09-01 19:29:44', 'active'),
(9, '2025-09-01 20:22:09', 'active'),
(9, '2025-09-15 20:22:09', 'ready');

INSERT INTO thesis_professor_notes (Thesis_ID, Professor_User_ID, Notes) VALUES
(10, 538, 'ηελλο'),
(9, 538, 'hello world'),
(10, 538, '0');

INSERT INTO trimelis (Thesis_ID, Trimelis_Professor_1, Trimelis_Professor_2, Trimelis_Professor_3) VALUES
(9, 560, 559, 538),
(10, 538, 559, 560),
(13, 538, NULL, NULL),
(11, 538, 560, 539),
(15, 538, 560, 559),
(16, 560, 538, 559),
(17, 560, 559, 538);

INSERT INTO trimelis_vathmologia (Thesis_ID, Trimelis_Professor_1_Grade, Trimelis_Professor_2_Grade, Trimelis_Professor_3_Grade, Trimelis_Final_Grade) VALUES
(10, '9.75', '9.35', '8.50', '9.20'),
(9, '4.60', '5.00', '9.00', '6.20');

INSERT INTO trimelous_invitation (Thesis_ID, Thesis_Student_Number, Professor_User_ID, Trimelous_Date, Invitation_Status) VALUES
(11, 10434012, 560, '2025-09-15 00:25:57', 'pending'),
(11, 10434012, 535, '2025-09-15 00:25:57', 'pending'),
(11, 10434012, 566, '2025-09-15 00:25:57', 'pending'),
(15, 10434000, 538, '2025-09-15 01:13:04', 'accept'),
(15, 10434000, 560, '2025-09-15 01:10:18', 'pending'),
(15, 10434000, 559, '2025-09-15 01:10:18', 'pending'),
(16, 10434001, 538, '2025-09-15 01:13:05', 'deny'),
(16, 10434001, 560, '2025-09-15 01:10:18', 'pending'),
(16, 10434001, 559, '2025-09-15 01:10:18', 'pending'),
(17, 10434002, 538, '2025-09-15 01:13:06', 'accept'),
(17, 10434002, 560, '2025-09-15 01:10:18', 'pending'),
(17, 10434002, 559, '2025-09-15 01:10:18', 'pending'),
(11, 10434012, 592, '2025-09-15 01:59:09', 'pending'),
(11, 10434012, 555, '2025-09-15 01:59:09', 'pending');



