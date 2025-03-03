<?php
namespace PopupBox\Core\Exceptions\Http;

use PopupBox\Core\Abstract\BaseException;

// server error (throw new class)
class ServerErrorException extends BaseException {
    public function __construct($message = 'Server Error.', $code = 0) {
        parent::__construct($message, $code, 503);
    }
}