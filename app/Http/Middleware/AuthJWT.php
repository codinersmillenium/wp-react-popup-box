<?php
namespace PopupBox\Http\Middleware;

use PopupBox\Config;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use WP_Error;

// middleware for JWT Token
class AuthJWT {

    public function handle($request) {
        // auth form browser with cookies worpress
        if (!is_user_logged_in()) {
            $user_id = wp_validate_auth_cookie('', 'logged_in');
            if ($user_id) {
                wp_set_current_user($user_id);
                wp_set_auth_cookie($user_id);
            }
        } 

        if (is_user_logged_in()) {
            return true;
        } else {
            // auth from token
            $auth = $request->get_header('Authorization');
            if (!$auth || !str_starts_with($auth, 'Bearer ')) {
                return new WP_Error('forbidden', 'JWT Token is missing.', ['status' => 401]);
            }

            $token = str_replace('Bearer ', '', $auth);
            try {
                $decoded = JWT::decode($token, new Key(Config::get('jwt_secret'), 'HS256'));
                $request->set_param('decoded_token', (array) $decoded);
                return true;
            } catch (\Exception $e) {
                return new WP_Error('forbidden', 'Invalid JWT Token.', ['status' => 403]);
            }
        }
    }
}
