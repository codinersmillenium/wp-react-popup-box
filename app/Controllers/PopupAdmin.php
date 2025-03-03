<?php
namespace PopupBox\Controllers;

use PopupBox\Core\Abstract\Controller;
use PopupBox\Core\Traits\SingleTrait;
use PopupBox\Config;
use PopupBox\Models\PopupBoxModel;

// controller to set the popup box management page
class PopupAdmin extends Controller {
    use SingleTrait;

    // init controller
    protected $controller = [
        'name'          => 'popup-admin',
        'view_url'      => 'app/Views/popup-template.php',
        'admin_menu'    => [
            'page_title'    => 'React Popup Box',
            'menu_title'    => 'Popup Box',            
            'capability'    => 'manage_options',            
            'menu_slug'     => 'react-popup-box',
            'icon_url'      => 'dashicons-feedback',
            'position'      => 6
        ]
    ];

    public function __construct() {

        // register admin menu
        $this->controller['admin_menu']['view'] = function() {
            return $this->views();
        };
        parent::__construct($this->controller);
    }

    // render page view admin menu
    public function views() {
        return $this->render_view($this->controller['view_url']);
    }

    // init hooks
    public function init_hooks() {
        $this->admin_menu();
        add_action('admin_enqueue_scripts', [ $this, 'enqueue_assets' ]);
        add_action('wp_ajax_option_select_page', function() {
            $data = $this->option_select_page();
            wp_send_json($data);
        });
        add_action('wp_ajax_get_token', function() {
            $id = get_current_user_id();
            $token = generate_token($id);
            wp_send_json($token);
        });
    }

    // data for select
    private function option_select_page() {
        $posts = PopupBoxModel::lists('post_type IN ("post", "page") 
                                        AND post_status = "publish" 
                                        AND popup_page_id IS NULL', 
                                    ["wp_posts" => ["RIGHT", "ID"]]);
        $result = [];
        foreach($posts as $post) {
            $row = [];
            $row['value'] = $post['ID'];
            $row['label'] = $post['post_title'].' - '.$post['post_type'];
            $result[] = $row;
        }
        return $result;
    }

    // include assets (js, scss, css, variable)
    public function enqueue_assets() {
        $version = Config::get('api_version', 'v1');
        $base_url = Config::get('base_url') . "/$version";
        $items = [
            ['type' => 'style', 'name' => 'popup-style', 'path' => 'assets/src/.dist/css/style.css'],
            ['type' => 'style', 'name' => 'popup-style-tailwind', 'path' => 'assets/src/.dist/css/tailwind.css'],
            ['type' => 'script', 'name' => 'popup-script', 'path' => 'assets/src/.dist/js/backend.bundle.js', 'deps' => ['wp-element', 'react', 'react-dom']]
        ];
        $this->assets($items);
        wp_localize_script('popup-script', 'globalScript', [            
            'nonce'                 => wp_create_nonce('popup_nonce'),
            'api_url'               => rest_url($base_url.'/popup'),
            'popup_enable'          => rest_url($base_url.'/popup_enable'),
            'admin_url'             => admin_url('admin-ajax.php')
        ]);        
    }

}