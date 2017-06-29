<?php
/**
 * 2017 WeeTeam
 *
 * @author    WeeTeam
 * @copyright 2017 WeeTeam
 * @license   http://www.gnu.org/philosophy/categories.html (Shareware)
 */

$sql = array();
$sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'calendar_services`;';
$sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'orders_calendar_services`;';