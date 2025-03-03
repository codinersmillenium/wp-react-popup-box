<?php
namespace PopupBox\Core\Exceptions;

use PopupBox\Core\Abstract\BaseException;
use Exception;

// global handling error -> new class->handle()
class ExceptionHandler extends BaseException {

    public function __construct($message = "An unexpected error occurred.", $code = 500) {
        parent::__construct($message, 0, $code);
    }

    public function handle() {
        $response_data = [
            'exception' => get_class($this)
        ];

        // log the exception
        self::log_exception($this);

        // return the JSON error response
        return $this->json_error($this->getMessage(), $this->get_status_code(), $response_data);
    }
    
    //log exception details to debug.log      
    public static function log_exception(Exception $exception) {
        $log_message = sprintf(
            "[%s] %s in %s on line %d\nStack trace:\n%s\n\n",
            date('Y-m-d H:i:s'),
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine(),
            $exception->getTraceAsString()
        );

        error_log($log_message, 3, WP_CONTENT_DIR . '/debug.log');
    }
}