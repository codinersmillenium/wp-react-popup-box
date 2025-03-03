<?php

// helper / utilities file
use Firebase\JWT\JWT;
use PopupBox\Config;

// validate request
if (!function_exists('validate')) {
    function validate($data, $rules) {
        foreach ($rules as $field => $field_rules) {
            foreach ($field_rules as $rule) {
                switch ($rule) {
                    case 'required':
                        if (empty($data[$field])) return ucfirst($field) . " tidak boleh kosong!";
                        break;
    
                    case (strpos($rule, 'min:') === 0):
                        $min_length = (int) str_replace('min:', '', $rule);
                        if (strlen($data[$field]) < $min_length) {
                            return ucfirst($field) . " harus minimal $min_length karakter!";
                        }
                        break;
    
                    case 'numeric':
                        if (!is_numeric($data[$field])) return ucfirst($field) . " harus berupa angka!";
                        break;
    
                    case 'valid_post':
                        if (!is_numeric($data[$field]) || !get_post($data[$field])) {
                            return "Postingan tidak ditemukan!";
                        }
                        break;
    
                    case 'can_delete':
                        if (!current_user_can('delete_post', $data[$field])) {
                            return "Anda tidak memiliki izin untuk menghapus postingan ini!";
                        }
                    case 'is_array':
                        if (!is_array($data[$field]) || empty($data[$field])) {
                            return ucfirst($field). " tidak boleh kosong.";
                        }
                        break;
                }
            }
        }
        return true;
    }    
}

// generate token JWT
if (!function_exists('generate_token')) {
    function generate_token($ID, $exp = null) {
        $secret_key = Config::get('jwt_secret');
        $payload = [
            'id' => $ID,
            'iat' => time()
        ];
        if ($exp) {
            $payload['exp'] = time() + intVal($exp);
        }
        $jwt = JWT::encode($payload, $secret_key, 'HS256');
        return $jwt;
    }
}