<?php

namespace PopupBox\Core\Abstract;

use PopupBox\Core\Interfaces\SingleInterface;
use PopupBox\Core\Interfaces\BaseInterface;
use PopupBox\Core\Traits\BaseTrait;

// abstract for controller
abstract class Controller implements SingleInterface, BaseInterface {
    use BaseTrait;

    protected $controller;
    protected $type_hook = 'init';
    
    public function __construct($args) {
        $this->controller = $args;        
        // init hooks
        add_action($this->type_hook, [$this, 'init_hooks']);
    }

    abstract public function init_hooks();

    // register admin menu
    public function add_admin_menu () {
        $item = $this->controller['admin_menu'];
        add_menu_page(
            $item['page_title'],
            $item['menu_title'],
            $item['capability'],
            $item['menu_slug'],
            $item['view'],
            $item['icon_url'],
            @$item['position']
        );
    }
}