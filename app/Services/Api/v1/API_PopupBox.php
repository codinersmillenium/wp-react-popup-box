<?php
namespace PopupBox\Services\Api\v1;

use PopupBox\Core\Exceptions\ExceptionHandler;
use PopupBox\Core\Exceptions\Http\NotFoundException;
use PopupBox\Core\Exceptions\Validation\ValidationException;
use PopupBox\Models\PopupBoxModel;
use Firebase\JWT\JWT;
use PopupBox\Config;
use Exception;

// api popupbox
class API_PopupBox {

    protected function get_id($request) {
        if (is_user_logged_in()) {
            return (int)get_current_user_id();
        } else {
            return (int)$request->get_param('decoded_token')['id'];
        }
    }    

    public function index() {
        try {                       
            $args = [
                'post_type'      => 'popup', 
                'post_status'    => ['publish', 'draft'],
                'numberposts'    => -1
            ];
            $posts = get_posts($args);
            foreach ($posts as $key => $value) {
                $value->page = PopupBoxModel::lists('popup_id='. $value->ID, 
                                ["wp_posts" => ["LEFT","ID"]]);
            }
            return rest_ensure_response([
                'success' => true,
                'data'    => $posts
            ]);
        } catch (Exception $e) {
            return (new ExceptionHandler($e->getMessage(), 500))->handle();
        }
    }

    public function show($request, $id) {
        try {                       
            $post = get_post($id);
            if ($post) {
                $post->page = PopupBoxModel::lists('popup_id='. $post->ID, 
                                ["wp_posts" => ["LEFT", "ID"]]);
            }
            return rest_ensure_response([
                'success' => true,
                'data'    => $post
            ]);
        } catch (Exception $e) {
            return (new ExceptionHandler($e->getMessage(), 500))->handle();
        }
    }

    public function create($request) {
        try {
            $validation = validate($request->get_params(), [
                'popup_title'       => ['required'],
                'popup_desc'        => ['required'],
                'popup_name'        => ['required'],
                'popup_type'        => ['required'],
                'popup_pages'       => ['required', 'is_array']
            ]);
            if ($validation !== true) {
                throw new ValidationException();
            } else {
                $popup_data = [
                    'post_title'     => sanitize_text_field($request->get_param('popup_title')),
                    'post_content'   => $request->get_param('popup_desc'),
                    'post_name'      => sanitize_text_field($request->get_param('popup_name')),
                    'post_status'    => 'publish',
                    'post_author'    => $this->get_id($request),
                    'post_type'      => 'popup',
                    'post_mime_type' => $request->get_param('popup_type')
                ];
                $popup_id = wp_insert_post($popup_data);
                if ($popup_id) {
                    foreach ($request->get_param('popup_pages') as $key => $value) {
                        $popup = [
                            'popup_id' => (int)$popup_id,
                            'popup_page_id' => (int)$value['value']
                        ];
                        PopupBoxModel::create($popup);
                    }
                    return rest_ensure_response([
                        'success' => true,
                        'message' => 'Popup created successfully!'
                    ]);
                }
                return (new ExceptionHandler('Failed to create popup', 500))->handle();
            }
        } catch (Exception $e) {
            return (new ExceptionHandler($e->getMessage(), 500))->handle();
        }
    }

    public function update($request, $id) {
        try {
            $validation = validate($request->get_params(), [
                'popup_title'       => ['required'],
                'popup_desc'        => ['required'],
                'popup_name'        => ['required'],
                'popup_type'        => ['required'],
                'popup_pages'       => ['required', 'is_array']
            ]);
            if ($validation !== true) {
                throw new ValidationException();
            } else {
                $id = (int)$id;
                $popup_data = [
                    'ID'             => $id,
                    'post_title'     => sanitize_text_field($request->get_param('popup_title')),
                    'post_content'   => $request->get_param('popup_desc'),
                    'post_name'      => sanitize_text_field($request->get_param('popup_name')),
                    'post_status'    => 'publish',
                    'post_author'    => $this->get_id($request),
                    'post_type'      => 'popup',
                    'post_mime_type' => $request->get_param('popup_type')
                ];
                $popup_id = wp_update_post($popup_data);
                if ($popup_id) {
                    $delete = PopupBoxModel::delete($id);
                    foreach ($request->get_param('popup_pages') as $key => $value) {
                        $popup = [
                            'popup_id' => $id,
                            'popup_page_id' => (int)$value['value']
                        ];
                        PopupBoxModel::create($popup);
                    }
                    return rest_ensure_response([
                        'success' => true,
                        'message' => 'Popup updated successfully!'
                    ]);
                }
                throw new ExceptionHandler('Failed to create popup', 500);
            }
        } catch (Exception $e) {
            return (new ExceptionHandler($e->getMessage(), 500))->handle();
        }
    }

    public function update_enable($request, $id) {
        try {
            if (empty($id)) {
                throw new ValidationException();
            } else {
                $enable = $request->get_param('enable') == 1 ? 'publish' : 'draft'; 
                $popup_data = [
                    'ID'             => $id,
                    'post_status'    => $enable,
                ];
                $popup_id = wp_update_post($popup_data);
                if ($popup_id) {
                    return rest_ensure_response([
                        'success' => true,
                        'message' => 'Popup updated successfully!'
                    ]);
                }
                return new ExceptionHandler('Failed to create popup', 500);
            }                
        } catch (Exception $e) {
            return (new ExceptionHandler($e->getMessage(), 500))->handle();
        }
    }

    public function delete($request, $id) {
        try {
            if (empty($id)) {
                throw new ValidationException();
            } else {
                PopupBoxModel::delete($id);
                wp_delete_post($id, true);
                return rest_ensure_response([
                    'success' => true,
                    'message' => 'Popup deleted successfully!'
                ]);
            }
        } catch (Exception $e) {
            return (new ExceptionHandler($e->getMessage(), 500))->handle();
        }
    }

}
