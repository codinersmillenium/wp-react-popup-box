<?php
namespace PopupBox\Models;
use PopupBox\Core\Abstract\Model;

// popup box model
class PopupBoxModel extends Model {
    protected static $table = 'popup_box';
    protected static $field = [
        'popup_id', 'popup_page_id'
    ];
    protected static $delete_id = 'popup_id';
    protected static $join_key = ['popup_page_id'];

    public static function get_render_popup($id) {
        global $wpdb;
        $tb = $wpdb->prefix.static::$table;
        $query = "SELECT 
                    post_name as popup_name,
                    post_title as popup_title,
                    post_content as popup_desc,
                    post_mime_type as popup_type
                    FROM wp_posts a
                    INNER JOIN {$tb} ON {$tb}.popup_id = a.ID
                    WHERE a.post_status = 'publish'
                    AND {$tb}.popup_page_id = {$id}";
        return $wpdb->get_row($query);
    }
}
