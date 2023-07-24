<?php

namespace App\Exceptions;

use Exception;

class ReservationException extends Exception
{
    protected $customMessage;

    public function __construct($message, $customMessage)
    {
        parent::__construct($message);
        $this->customMessage = $customMessage;
    }

    public function render($request)
    {
        return response()->json([
            'error' => 'Custom Query Exception',
            'message' => $this->customMessage,
        ], 500);
    }
}
