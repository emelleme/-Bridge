<?php
class MessageAdmin extends ModelAdmin {

    //public static $collection_controller_class = "EmergencyAlertAdmin_RecordController";
    public static $managed_models = array(   //since 2.3.2
      'Event'
    );

	static $url_segment = 'events'; // will be linked as /admin/products
    static $menu_title = 'Event Manager';
    static $model_importers = array();


    public function init() {
            parent::init();

    }
}
