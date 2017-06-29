<?php

class OrdersCalendarServices extends ObjectModel
{
    public $id_orders_service;
    public $id_product;
    public $id_service;
    public $name;
    public $phone;
    public $start;
    public $end;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'orders_calendar_services',
        'primary' => 'id_orders_service',
        'multilang' => false,
        'fields' => array(
            'id_orders_service' => array('type' => self::TYPE_INT),
            'id_product' => array('type' => self::TYPE_INT),
            'id_service' => array('type' => self::TYPE_INT),
            'name' => array('type' => self::TYPE_STRING),
            'phone' => array('type' => self::TYPE_STRING),
            'start' => array('type' => self::TYPE_DATE),
            'end' => array('type' => self::TYPE_DATE),
        ),
    );

    public static function getOrderServicesByServiceId($id_service) {
        $sql = 'SELECT * FROM ' . _DB_PREFIX_ . 'orders_calendar_services
         WHERE id_service=' . (int)$id_service;
        if ($row = Db::getInstance()->executeS($sql)) {
            return $row;
        }
        return false;
    }

    public static function checkIssetService() {
        $id_service = Tools::getValue('id_service');
        $sql = 'SELECT  step_time FROM ' . _DB_PREFIX_ . 'calendar_services
         WHERE id_calendar_services=' . (int)$id_service;
        $result = Db::getInstance()->getRow($sql);
        return $result;
    }

    public static function removeOrdersByIdService() {
        $id_service = Tools::getValue('id_service');
        $sql = 'DELETE FROM ' . _DB_PREFIX_ . 'orders_calendar_services
         WHERE id_service=' . (int)$id_service;
        if (Db::getInstance()->execute($sql)) {
            return true;
        }
        return false;
    }
}