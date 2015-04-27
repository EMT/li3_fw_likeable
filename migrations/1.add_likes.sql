CREATE TABLE `likes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `created` int(11) unsigned NOT NULL,
  `updated` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `user_ip` varchar(60) NOT NULL,
  `user_custom_identifier` varchar(60) NOT NULL,
  `liked_entity_id` int(11) unsigned NOT NULL,
  `liked_entity_type` varchar(60) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;