<?php
/**
 * 2017 WeeTeam
 *
 * @author    WeeTeam
 * @copyright 2017 WeeTeam
 * @license   http://www.gnu.org/philosophy/categories.html (Shareware)
 */

$sql = array();

$sql[] = "CREATE TABLE IF NOT EXISTS `" . _DB_PREFIX_ . "calendar_services` (
`id_calendar_services` int(11) NOT NULL AUTO_INCREMENT,
`id_product` int(11) NOT NULL,
`start` TIME NOT NULL,
`end` TIME NOT NULL,
`type_step` int(2) NOT NULL,
`step_time` TIME NOT NULL,
`step_days` int(2) NOT NULL,
`service_active` int(2) NOT NULL,
`working_days` VARCHAR(100) NOT NULL,
PRIMARY KEY (`id_calendar_services`)
)";


$sql[] = "CREATE TABLE IF NOT EXISTS `" . _DB_PREFIX_ . "orders_calendar_services` (
`id_orders_service` int(11) NOT NULL AUTO_INCREMENT,
`id_product` int(11) NOT NULL,
`id_service` int(11) NOT NULL,
`name` VARCHAR(100) NOT NULL,
`phone` VARCHAR(100) NOT NULL,
`start` TIME NOT NULL,
`end` TIME NOT NULL,
PRIMARY KEY (`id_orders_service`)
)";
