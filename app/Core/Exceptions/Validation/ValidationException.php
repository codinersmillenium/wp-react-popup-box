<?php
namespace PopupBox\Core\Exceptions\Validation;

use PopupBox\Core\Abstract\BaseException;

// validation error (throw new class)
class ValidationException extends BaseException {
    public function __construct($message = 'Validation failed.', $code = 0) {
        parent::__construct($message, $code, 422);
    }
}