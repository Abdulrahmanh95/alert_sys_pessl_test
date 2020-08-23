CREATE TABLE `user_thresholds`
(
    `id`      int(11)        NOT NULL AUTO_INCREMENT,
    `battery` decimal(10, 2) NOT NULL DEFAULT '2300.00',
    `rh_avg`  decimal(10, 2) NOT NULL DEFAULT '90.00',
    `air_avg` decimal(10, 2) NOT NULL DEFAULT '13.00',
    PRIMARY KEY (`id`),
    CONSTRAINT `user_thresholds_users_id_fk` FOREIGN KEY (`id`) REFERENCES `users` (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 2
  DEFAULT CHARSET = utf8

