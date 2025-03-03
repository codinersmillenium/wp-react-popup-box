<?php
namespace PopupBox\Core\Traits;

use WP_REST_Response;

trait JsonErrorTrait {

    // return a WP JSON error response.
    public function json_error($message, $status = 500, $data = []) {
        $response = [
            'status'  => $status,
            'error'   => true,
            'message' => $message
        ];

        if (!empty($data)) {
            $response = array_merge($response, $data);
        }

        return new WP_REST_Response($response, $status);
    }
}
