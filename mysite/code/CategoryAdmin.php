<?php
class CategoryAdmin extends ModelAdmin {

    //public static $collection_controller_class = "EmergencyAlertAdmin_RecordController";
    public static $managed_models = array(   //since 2.3.2
      'Category'
    );

	static $url_segment = 'category'; // will be linked as /admin/products
    static $menu_title = 'Category Manager';
    static $model_importers = array();


    public function init() {
            parent::init();

    }
}
