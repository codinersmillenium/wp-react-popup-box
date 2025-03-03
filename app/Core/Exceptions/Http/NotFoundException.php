<?php
namespace PopupBox\Core\Exceptions\Http;

use PopupBox\Core\Abstract\BaseException;

// notfound exception (throw new class)
class NotFoundException extends BaseException {
    public function __construct($message = 'Resource not found.', $code = 0) {
        parent::__construct($message, $code, 404);
    }
}
