CREATE TABLE IF NOT EXISTS `user_notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `recipient_id` int NOT NULL,
  `actor_id` int DEFAULT NULL,
  `submission_id` int NOT NULL,
  `type` enum('resubmitted','revision','approved') NOT NULL,
  `message` varchar(255) NOT NULL,
  `target_url` varchar(255) NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `read_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `recipient_id` (`recipient_id`),
  KEY `actor_id` (`actor_id`),
  KEY `submission_id` (`submission_id`),
  CONSTRAINT `user_notifications_ibfk_1` FOREIGN KEY (`recipient_id`) REFERENCES `tbl_user` (`id_user`) ON DELETE CASCADE,
  CONSTRAINT `user_notifications_ibfk_2` FOREIGN KEY (`actor_id`) REFERENCES `tbl_user` (`id_user`) ON DELETE SET NULL,
  CONSTRAINT `user_notifications_ibfk_3` FOREIGN KEY (`submission_id`) REFERENCES `submissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
