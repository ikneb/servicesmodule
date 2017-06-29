<?php
/**
 * 2017 WeeTeam
 *
 * @author    WeeTeam <info@weeteam.net>
 * @copyright 2017 WeeTeam
 * @license   http://www.gnu.org/philosophy/categories.html (Shareware)
 */
class ServicesProduct extends Module
{
    public function __construct()
    {
        include_once(_PS_MODULE_DIR_ . 'servicesproduct/classes/CalendarServices.php');
        include_once(_PS_MODULE_DIR_ . 'servicesproduct/classes/OrdersCalendarServices.php');


        if (!defined('_PS_MODULE_DIR_')) {
            define('_PS_MODULE_DIR_', _PS_ROOT_DIR_ . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR);
        }

        $this->name = 'servicesproduct';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->bootstrap = true;
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        parent::__construct();
        $this->page = basename(__FILE__, '.php');
        $this->displayName = $this->l('Services module');
        $this->description = $this->l('Services module');
        $this->description_big = '';
        $this->author = 'Weeteam';
//      $this->module_key = 'f6e24f90f11de18aca6fe9a87d02210d';
    }


    public function install()
    {
        if (!parent::install()
            || !$this->registerHook('addProduct')
            || !$this->registerHook('displayAdminProductsExtra')
            || !$this->registerHook('actionAdminControllerSetMedia')
            || !$this->registerHook('displayFooterProduct')
            || !$this->registerHook('actionFrontControllerSetMedia')
        ) {
            return false;
        }

        $this->installDb();

        $parent_tab = new Tab();
        $parent_tab->name[$this->context->language->id] = $this->l('Services calendar');
        $parent_tab->class_name = 'AdminServices';
        $parent_tab->id_parent = 0; // Home tab
        $parent_tab->module = $this->name;
        $parent_tab->add();

        $tab = new Tab();
        $tab->name[$this->context->language->id] = $this->l('Calendar');
        $tab->class_name = 'AdminServicesProduct';
        $tab->id_parent = $parent_tab->id;
        $tab->module = $this->name;
        $tab->add();

        return true;
    }

    function hookActionFrontControllerSetMedia() {
        $this->context->controller->addCSS(_PS_MODULE_DIR_ . 'servicesproduct/views/css/front/style.css', 'all');
        $this->context->controller->addJS(_PS_MODULE_DIR_ . 'servicesproduct/views/js/front/moment.js');
        $this->context->controller->addJS(_PS_MODULE_DIR_ . 'servicesproduct/views/js/front/module.js');

        $id_product = Tools::getValue('id_product');
        $service = CalendarServices::getCalendarServiceByProduct($id_product);
        $order_services = OrdersCalendarServices::getOrderServicesByServiceId($service['id_calendar_services']);
        $service['working_days'] = json_encode(unserialize($service['working_days']));

        if ($id_product) {
            Media::addJsDef(array(
                'services' => $service,
                'order_services' =>  $order_services
            ));
        }
    }

    function hookDisplayFooterProduct () {
        $id_product = Tools::getValue('id_product');
        if(!CalendarServices::getCalendarServiceByProduct($id_product)) {
           return false;
        }
        $service  = CalendarServices::getCalendarServiceByProduct($id_product);
        $order_services = OrdersCalendarServices::getOrderServicesByServiceId($service['id_calendar_services']);
        $this->context->smarty->assign(array(
            'id_product' => $id_product,
            'service' => $service,
            'order_services' =>  $order_services
        ));
        return $this->context->smarty->fetch(
            _PS_MODULE_DIR_ . 'servicesproduct/views/templates/front/admin_calendar.tpl');
    }

    function hookAddProduct()
    {

    }

    function hookActionAdminControllerSetMedia()
    {
        if ($this->context->controller->controller_name == 'AdminProducts') {
            $this->context->controller->addCss($this->_path . 'views/css/admin/product_tab.css');
            $this->context->controller->addJS($this->_path . 'views/js/admin/product_tab.js');
        }
        Media::addJsDef(array(
            'mod_token' =>  Tools::getAdminTokenLite('AdminServicesProduct'),
        ));
    }

    function hookDisplayAdminProductsExtra($product)
    {
        Media::addJsDef(array(
            'mod_token' =>  Tools::getAdminTokenLite('AdminServicesProduct')
        ));
        $service = '';
        $id_product = (int)$product['id_product'];
        $product = new Product($id_product,
            true,
            $this->context->language->id,
            $this->context->shop->id);

        if ($product->is_virtual == 1) {
            $service = CalendarServices::getCalendarServiceByProduct($id_product);

            $time_start = $this->getTimes($service['start']);
            $time_end = $this->getTimes($service['end']);
            $working_days = implode(',',unserialize($service['working_days']));
            $this->smarty->assign(array(
                'id_product' => $id_product,
                'options_select_start' => $time_start,
                'options_select_end' => $time_end,
                'service' => $service,
                'working_days' => $working_days,
                'step_time' => $this->getStepTime($service['step_time']),
                'version' => version_compare(_PS_VERSION_, '1.7', '<'),
                'product_tab_for_7' => _PS_MODULE_DIR_ . 'servicesproduct/views/templates/admin/product_tab_for_7.tpl',
                'product_tab_for_6' => _PS_MODULE_DIR_ . 'servicesproduct/views/templates/admin/product_tab_for_6.tpl'
            ));
        }
        $this->smarty->assign(array(
            'virtual' => $product->is_virtual
        ));

        return $this->display(__FILE__, 'views/templates/admin/product_tab.tpl');
    }


    public function installDb()
    {
        $sql = array();
        include(dirname(__FILE__) . '/sql/install.php');
        foreach ($sql as $s) {
            if (!Db::getInstance()->execute($s)) {
                return false;
            }
        }
        return true;
    }

    public function uninstall()
    {
        $sql = array();
        include(dirname(__FILE__) . '/sql/uninstall.php');
        foreach ($sql as $s) {
            if (!Db::getInstance()->execute($s)) {
                return false;
            }
        }

        if (!parent::uninstall() ||
            !$this->deleteTab()
        ) {
            return false;
        }


        return true;
    }

    public function getStepTime($default = '30', $interval = '+30 minutes')
    {
        $output = '';

        $current = strtotime('00:30');
        $end = strtotime('8:00');

        while ($current <= $end) {
            $time = date('H:i:s', $current);
            $sel = ($time == $default) ? ' selected' : '';
            $output .= "<option value=\"{$time}\" {$sel}>" .$time. '</option>';
            $current = strtotime($interval, $current);
        }
        return $output;
    }


    public function deleteTab()
    {
        $id_tab = Tab::getIdFromClassName('AdminServices');
        $tab = new Tab($id_tab);

        $id_parent_tab = Tab::getIdFromClassName('AdminServicesProduct');
        $parent_tab = new Tab($id_parent_tab);
        if ($tab->delete() && $parent_tab->delete()) {
            return true;
        }
    }

    public function getTimes($default = '18:00', $interval = '+30 minutes')
    {
        $output = '';

        $current = strtotime('00:00');
        $end = strtotime('23:59');

        while ($current <= $end) {
            $time = date('H:i:s', $current);
            $sel = ($time == $default) ? ' selected' : '';

            $output .= "<option value=\"{$time}\"{$sel}>" . date('h:i A', $current) . '</option>';
            $current = strtotime($interval, $current);
        }

        return $output;
    }

   public function postProcess() {
       $service = '';
       $id_service = Tools::getValue('id_service') ? Tools::getValue('id_service') : '';
       $id_product = Tools::getValue('id_product') ? Tools::getValue('id_product') : '';
       $stepTime =  Tools::getValue('step_time');
       $stepDays =  Tools::getValue('step_days');

       if (empty($id_service)) {
           $service = new CalendarServices();
       } else {
           $service = new CalendarServices($id_service);
       }
       $service->id_product = $id_product;
       $service->start = Tools::getValue('start');
       $service->end = Tools::getValue('end');
       $service->type_step = (int)Tools::getValue('typeStep');
       $service->step_time = $stepTime;
       $service->step_days = (int)$stepDays;
       $service->service_active = 1/*(int)Tools::getValue('service_active')*/ ;
       $service->working_days = serialize(Tools::getValue('working_days'));

       if (empty($id_service)) {
           $service->add();
       } else {
           $service->update();
       }
   }


}