CREATE TABLE `users`
(
    `id`    int(11)     NOT NULL AUTO_INCREMENT,
    `name`  varchar(40) NOT NULL,
    `email` varchar(40) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `users_email_uindex` (`email`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 2
  DEFAULT CHARSET = utf8

