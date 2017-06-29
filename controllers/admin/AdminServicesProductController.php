<?php

class AdminServicesProductController extends ModuleAdminController
{
    public function __construct()
    {
        require_once(_PS_MODULE_DIR_ . 'servicesproduct/classes/CalendarServices.php');
        require_once(_PS_MODULE_DIR_ . 'servicesproduct/classes/OrdersCalendarServices.php');

        $this->bootstrap = true;
        $this->required_database = true;
        $this->required_fields = array(
            'id_calendar_services',
            'id_product',
            'start',
            'end',
            'type_step',
            'step_time',
            'step_days',
            'service_active',
            'working_days'
        );
        $this->table = 'calendar_services';
        $this->className = 'CalendarServices';
        $this->lang = false;
        $this->context = Context::getContext();
        parent::__construct();


        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?'),
                'icon' => 'icon-trash'
            )
        );
        $this->allow_export = true;

        $this->fields_list = array(
            'id_calendar_services' => array(
                'title' => $this->l('ID'),
                'align' => 'center'
            ),
            'id_product' => array(
                'title' => $this->l('ID Product'),
                'align' => 'center'
            ),
            'start' => array(
                'title' => $this->l('Start time'),
                'align' => 'center'
            ),
            'end' => array(
                'title' => $this->l('End time'),
                'align' => 'center'
            ),
            'step_time' => array(
                'title' => $this->l('Step time '),
                'align' => 'center'
            )
        );
    }

    public function renderForm()
    {
        $id_calendar_services = Tools::getValue('id_calendar_services');
        $order_services = OrdersCalendarServices::getOrderServicesByServiceId($id_calendar_services);
        $service = new CalendarServices($id_calendar_services);
        $service->working_days = json_encode(unserialize($service->working_days));
        Media::addJsDef(array(
            'services' => $service,
            'order_services' =>  $order_services,
            'id_product' => 8
        ));
        $this->context->smarty->assign(array(
            'id_product' => $service->id_product,
            'service' => $service,
            'order_services' =>  $order_services
        ));
        return $this->context->smarty->fetch(
            _PS_MODULE_DIR_ . 'servicesproduct/views/templates/admin/admin_calendar.tpl');
    }


    public function setMedia()
    {
        parent::setMedia();
        $this->context->controller->addCSS(_PS_MODULE_DIR_ . 'servicesproduct/views/css/style.css', 'all');
        $this->context->controller->addJS(_PS_MODULE_DIR_ . 'servicesproduct/views/js/admin/module.js');
    }


}
