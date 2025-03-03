<?php
use PopupBox\Core\Traits\SingleTrait;
class Database {

    use SingleTrait;
    private $table_popup;
    private $table_popup_page;

    public function __construct() {
        global $wpdb;
        $this->table_popup_page = $wpdb->prefix . 'popup_box'; // table name
    }

    // create database
    public function install() { 
        try {
            global $wpdb;
            $charset_collate = $wpdb->get_charset_collate();

            // check table exists
            if ($this->check_table_exists($this->table_popup_page) === false) {
                $sql_popup_page = "CREATE TABLE $this->table_popup_page (
                    ID bigint(20) NOT NULL AUTO_INCREMENT,
                    popup_id bigint(20) NOT NULL,
                    popup_page_id bigint(20) NOT NULL,
                    PRIMARY KEY (ID)
                ) $charset_collate;";

                require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                $result = dbDelta($sql_popup_page);
                if ($wpdb->last_error) {
                    return false;
                }
            }
            return true;
        } catch (\Throwable $th) {
            return false;
        }       
    }

    // check table exists
    private function check_table_exists($table_name) {
        global $wpdb;
        $result = $wpdb->get_results("SHOW TABLES LIKE '$table_name'");
        return !empty($result);
    }
}