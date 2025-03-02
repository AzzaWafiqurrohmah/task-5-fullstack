<?php

namespace App\Exceptions\Api;

use App\ApiResponser;
use Exception;

class FailedValidation extends Exception
{
    use ApiResponser;
    protected $errors;

    public function __construct($errors)
    {
        $this->errors = $errors;
    }

    public function render()
    {
        return $this->validationError($this->errors);
    }
}