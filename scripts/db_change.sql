--
-- Table structure for table `course_team`
--
DROP TABLE IF EXISTS `course_team`;
CREATE TABLE `course_team` (
  `team_id` int(11) NOT NULL AUTO_INCREMENT,
  `term` varchar(255) DEFAULT '',
  `course_name` varchar(255) DEFAULT '',
  `teacher_name` varchar(255) DEFAULT '',
  `class_week_time` varchar(255) DEFAULT '',
  `class_id_in_school` varchar(255) DEFAULT '',
  PRIMARY KEY (`team_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `course_team_relation`
--
DROP TABLE IF EXISTS `course_team_relation`;
CREATE TABLE `course_team_relation` (
  `relation_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(48) NOT NULL,
  `team_id` int(11) NOT NULL,
  PRIMARY KEY (`relation_id`),
  KEY `user_id` (`user_id`),
  KEY `team_id` (`team_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `privilege_distribution` ADD COLUMN `manage_course_team` tinyint(4) DEFAULT 0;

UPDATE `privilege_distribution` SET `manage_course_team` = 1 WHERE `group_name` = 'administrator';
UPDATE `privilege_distribution` SET `manage_course_team` = 1 WHERE `group_name` = 'teacher';
UPDATE `privilege_distribution` SET `manage_course_team` = 0 WHERE `group_name` = 'exam_user';
UPDATE `privilege_distribution` SET `manage_course_team` = 0 WHERE `group_name` = 'hznu_viewer';
UPDATE `privilege_distribution` SET `manage_course_team` = 1 WHERE `group_name` = 'root';
UPDATE `privilege_distribution` SET `manage_course_team` = 0 WHERE `group_name` = 'source_browser';
UPDATE `privilege_distribution` SET `manage_course_team` = 0 WHERE `group_name` = 'teacher_assistant';