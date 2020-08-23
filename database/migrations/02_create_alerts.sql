CREATE TABLE `alerts`
(
    `id`           int(11)     NOT NULL AUTO_INCREMENT,
    `type`         varchar(40) NOT NULL,
    `user_id`      int(11)     NOT NULL,
    `sent`         tinyint(4)  NOT NULL DEFAULT '0',
    `created_at`   datetime    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `delivered_at` datetime,
    PRIMARY KEY (`id`),
    KEY `type_index` (`type`),
    KEY `user_fk` (`user_id`),
    CONSTRAINT `user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 30
  DEFAULT CHARSET = utf8

