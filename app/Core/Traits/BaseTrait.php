<?php
namespace PopupBox\Core\Traits;

// app trait
trait BaseTrait {

    // add admin menu
    public function admin_menu() {
        add_action('admin_menu', [$this, 'add_admin_menu']);
    }

    // render view
    public function render_view($view, $data = []) {
        if (!empty($data) && is_array($data)) {
            extract($data);
        }
        include PLUGIN_PATH. $view;
    }

    // generate assets
    public function assets($item) {
        foreach ($item as $key => $value) {
            switch ($value['type']) {
                case 'style':
                    wp_enqueue_style($value['name'], PLUGIN_URL . $value['path'], $value['deps'] ?? [], $value['ver'] ?? '1.0', $value['loc'] ?? '');
                    break;
                case 'script':
                    wp_enqueue_script($value['name'], PLUGIN_URL . $value['path'], $value['deps'] ?? ['wp-element'], $value['vers'] ?? '1.0', $value['loc'] ?? true);
                    break;
                default:
                    return 'asset not initialized';
                    break;
            }
        }
    }

}