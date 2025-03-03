<?php
namespace PopupBox\Controllers;

use PopupBox\Models\PopupBoxModel;
use PopupBox\Core\Abstract\Controller;
use PopupBox\Core\Traits\SingleTrait;
use PopupBox\Config;

// controller to render popup in page or posts
class PopupBox extends Controller {
    use SingleTrait;
    
    protected $controller = [
        'name'          => 'popup-box',
        'view_url'      => 'app/Views/popup-template.php'
    ];
    protected $type_hook = 'wp';

    public function __construct() {
        parent::__construct($this->controller);
    }

    // render view
    public function views() {
        return $this->render_view($this->controller['view_url']);
    }

    // init hooks
    public function init_hooks() {
        if (!is_admin()) {
            $this->enqueue_assets();
            return $this->views();
        }
    }

    // assets
    public function enqueue_assets() {
        $items = [
            ['type' => 'style', 'name' => 'popup-style', 'path' => 'assets/src/.dist/css/style.css'],
            ['type' => 'style', 'name' => 'popup-style-tailwind', 'path' => 'assets/src/.dist/css/tailwind.css'],
            ['type' => 'script', 'name' => 'popup-script', 'path' => 'assets/src/.dist/js/frontend.bundle.js', 'deps' => ['react', 'react-dom']]
        ];
        $id = get_the_ID();
        $data = PopupBoxModel::get_render_popup($id);
        $this->assets($items);
        wp_localize_script('popup-script', 'globalScript', [            
            'data_popup' => $data
        ]);
    }

}