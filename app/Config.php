<?php
namespace PopupBox;

// load config app in ../config/app.php
class Config {
    protected static $config = [];

    public static function load($file) {
        $path = plugin_dir_path(__FILE__) . '../config/' . $file . '.php';
        if (file_exists($path)) {
            self::$config = require $path;
        }
    }

    public static function get($key, $default = null) {
        return self::$config[$key] ?? $default;
    }
}
Config::load('app');