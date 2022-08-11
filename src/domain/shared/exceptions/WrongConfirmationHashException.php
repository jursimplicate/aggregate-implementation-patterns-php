<?php

namespace domain\shared\exceptions;

class WrongConfirmationHashException extends \Exception
{
    public function __construct()
    {
        parent::__construct("confirmation hash does not match");
    }
}