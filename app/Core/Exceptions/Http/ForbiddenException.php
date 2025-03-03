<?php
namespace PopupBox\Core\Exceptions\Http;

use PopupBox\Core\Abstract\BaseException;

// forbidden exception (throw new class)
class ForbiddenException extends BaseException {
    public function __construct($message = 'You do not have permission.', $code = 0) {
        parent::__construct($message, $code, 403);
    }

    public function handle() {
        return $this->json_error($this->$message, $this->code);
    }
}