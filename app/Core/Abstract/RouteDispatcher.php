<?php
namespace PopupBox\Core\Abstract;

use PopupBox\Core\Interfaces\RouteInterface;
use WP_REST_Request;

// route dispatcher for custom route
abstract class RouteDispatcher implements RouteInterface{    
    protected static $base_url;
    protected static $routes = [];

    public static function set_base_url($base_url) {
        self::$base_url = $base_url;
    }

    // set method, esc..
    public static function add_route($method, $route, $controller, $middleware = []) {
        self::$routes[] = compact('method', 'route', 'controller', 'middleware');
    }

    // register router
    public static function register_routes() {        
        add_action('rest_api_init', function () {
            // add cors
            header("Access-Control-Allow-Origin: *");
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
            header("Access-Control-Allow-Headers: Content-Type, Authorization");
            if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
                exit(0);
            }
            foreach (self::$routes as $route) {
                // get $route pattern -> sample index/{id}
                $route_pattern = preg_replace('/\{([^}]+)\}/', '(?P<$1>[^/]+)', $route['route']);
                register_rest_route(self::$base_url, $route_pattern, [
                    'methods' => $route['method'],
                    'callback' => function ($request) use ($route) {
                        return static::dispatch($request, $route);
                    },
                    'permission_callback' => '__return_true',
                ]);
            }
        }, 15);
    }

    abstract protected static function dispatch(WP_REST_Request $request, $route);
}