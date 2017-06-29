<?php

class CalendarServices extends ObjectModel
{
    public $id_calendar_services;
    public $id_product;
    public $start;
    public $end;
    public $type_step;
    public $step_time;
    public $step_days;
    public $service_active;
    public $working_days;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'calendar_services',
        'primary' => 'id_calendar_services',
        'multilang' => false,
        'fields' => array(
            'id_calendar_services' => array('type' => self::TYPE_INT),
            'id_product' => array('type' => self::TYPE_INT),
            'start' => array('type' => self::TYPE_DATE),
            'end' => array('type' => self::TYPE_DATE),
            'type_step' => array('type' => self::TYPE_INT),
            'step_time' => array('type' => self::TYPE_DATE),
            'step_days' => array('type' => self::TYPE_INT),
            'service_active' => array('type' => self::TYPE_INT),
            'working_days' => array('type' => self::TYPE_STRING),
        ),
    );


    public static function getCalendarServiceByProduct($id_product)
    {
        $sql = 'SELECT * FROM ' . _DB_PREFIX_ . 'calendar_services
         WHERE id_product=' . (int)$id_product;
        if ($row = Db::getInstance()->getRow($sql)) {
            return $row;
        }
        return false;
    }
}