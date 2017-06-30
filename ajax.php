<?php
/**
 * 2017 WeeTeam
 *
 * @author    WeeTeam
 * @copyright 2016 WeeTeam
 * @license   http://www.gnu.org/philosophy/categories.html (Shareware)
 */

require_once(dirname(__FILE__) . '../../../config/config.inc.php');
require_once(dirname(__FILE__) . '../../../init.php');
require_once(dirname(__FILE__) . '/classes/CalendarServices.php');
require_once(dirname(__FILE__) . '/classes/OrdersCalendarServices.php');

switch (Tools::getValue('ajax')) {

    case 'save_service':
        $service = '';
        $id_service = Tools::getValue('id_service') ? Tools::getValue('id_service') : '';
        $id_product = Tools::getValue('id_product') ? Tools::getValue('id_product') : '';
        $stepTime =  Tools::getValue('stepTime');
        $stepDays =  Tools::getValue('stepDays');

        if (empty($id_service)) {
            $service = new CalendarServices();
        } else {
            $service = new CalendarServices($id_service);
        }
        if (Tools::getValue('remove_orders')) {
            OrdersCalendarServices::removeOrdersByIdService();
        }
        $service->id_product = $id_product;
        $service->start = Tools::getValue('start');
        $service->end = Tools::getValue('end');
        $service->type_step = (int)Tools::getValue('typeStep');
        $service->step_time = $stepTime;
        $service->step_days = (int)$stepDays;
        $service->service_active = (int)Tools::getValue('service_active');
        $service->working_days = serialize(Tools::getValue('working_days'));

        if (empty($id_service)) {
            $service->add();
        } else {
            $service->update();
        }
        echo $service->id;
        break;
    case 'add_service':
        $order_service  = new OrdersCalendarServices();
        $order_service->id_service = Tools::getValue('id_service');
        $order_service->id_product = Tools::getValue('id_product');
        $order_service->name = Tools::getValue('name');
        $order_service->phone = Tools::getValue('phone');
        $order_service->start = Tools::getValue('start');
        $order_service->end = Tools::getValue('end');
        $order_service->add();
        $order_service->sendNotification();

        echo $order_service->id;
        break;
    case 'checkService':
        echo json_encode(OrdersCalendarServices::checkIssetService());
        break;
    case 'remove_service':
        $order_service  = new OrdersCalendarServices((int)Tools::getValue('id'));
        if ($order_service->delete()) {
            echo true;
        }
        echo false;
        break;

}