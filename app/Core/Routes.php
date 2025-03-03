<?php
namespace PopupBox\Core;

use PopupBox\Core\Abstract\RouteDispatcher;
use PopupBox\Core\Exceptions\Validation\ValidationException;
use PopupBox\Core\Exceptions\Http\ForbiddenException;
use PopupBox\Core\Exceptions\Http\NotFoundException;
use PopupBox\Core\Exceptions\Http\ServerErrorException;
use WP_REST_Request;
use WP_Error;


class Routes extends RouteDispatcher {

    // dispatch route
    protected static function dispatch(WP_REST_Request $request, $route) {
        try {
            // middleware        
            foreach ($route['middleware'] as $middleware) {
                if (!class_exists($middleware)) {
                    return new WP_Error('middleware_not_found', "Middleware $middleware not found.", ['status' => 500]);
                }
                $middleware_ = new $middleware();
                $result = $middleware_->handle($request);
                if (is_wp_error($result)) {
                    return $result;
                }
            }

            // include controller (sample: "Controller@method")
            if (strpos($route['controller'], '@') === false) {
                return new WP_Error('invalid_controller_format', "Controller harus dalam format 'ControllerClass@method'.", ['status' => 500]);
            }

            [$controller, $method] = explode('@', $route['controller']);

            // check if controller exists
            if (!class_exists($controller)) {
                return new WP_Error('controller_not_found', "Controller $controller not found!.", ['status' => 500]);
            }        
            if (!method_exists($controller, $method)) {
                return new WP_Error('method_not_found', "Method $method not found in $controller.", ['status' => 500]);
            }

            // init controller

            $controller_ = new $controller();       
            $response = call_user_func_array([$controller_, $method], array_merge([$request], [$request->get_param('id')])); 
            return $response;

        } catch (ValidationException $e) {
            return new WP_Error('validation_error', $e->getMessage(), ['status' => 422]);
        } catch (ForbiddenException $e) {            
            return new WP_Error('forbidden_error', $e->getMessage(), ['status' => 403]);
        } catch (NotFoundException $e) {
            return new WP_Error('not_found_error', $e->getMessage(), ['status' => 404]);
        } catch (\Exception $e) {
            return new WP_Error('server_error', 'Terjadi kesalahan pada server.', ['status' => 500]);
        }
    }
}
