CREATE TABLE `station_payloads`
(
    `id`      int(11)        NOT NULL AUTO_INCREMENT,
    `battery` decimal(10, 2) NOT NULL DEFAULT '0.00',
    `solar`   decimal(10, 2) NOT NULL DEFAULT '0.00',
    `rain`    decimal(10, 2) NOT NULL DEFAULT '0.00',
    `air_avg` decimal(10, 2) NOT NULL DEFAULT '0.00',
    `air_mn`  decimal(10, 2) NOT NULL DEFAULT '0.00',
    `rh_mx`   decimal(10, 2) NOT NULL DEFAULT '0.00',
    `rh_mn`   decimal(10, 2) NOT NULL DEFAULT '0.00',
    `air_mx`  decimal(10, 2) NOT NULL DEFAULT '0.00',
    `dt_mx`   decimal(10, 2) NOT NULL DEFAULT '0.00',
    `dt_avg`  decimal(10, 2) NOT NULL DEFAULT '0.00',
    `dt_mn`   decimal(10, 2) NOT NULL DEFAULT '0.00',
    `rh_avg`  decimal(10, 2) NOT NULL DEFAULT '0.00',
    `vpd_mn`  decimal(10, 2) NOT NULL DEFAULT '0.00',
    `dew_avg` decimal(10, 2) NOT NULL DEFAULT '0.00',
    `vpd_avg` decimal(10, 2) NOT NULL DEFAULT '0.00',
    `leaf`    int(11)        NOT NULL DEFAULT '0',
    `dew_mn`  decimal(10, 2) NOT NULL DEFAULT '0.00',
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 122
  DEFAULT CHARSET = utf8

