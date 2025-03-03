<?php
namespace PopupBox\Core\Abstract;

use PopupBox\Core\Traits\JsonErrorTrait;
use Exception;

// abstract class handling error app
abstract class BaseException extends Exception {
    use JsonErrorTrait;
    protected $status_code;

    public function __construct($message = "", $code = 0, $status_code = 500) {
        parent::__construct($message, $code);
        $this->status_code = $status_code;
    }

    public function get_status_code() {
        return $this->status_code;
    }
}
