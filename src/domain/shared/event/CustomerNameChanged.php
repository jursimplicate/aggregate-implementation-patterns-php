<?php

namespace domain\shared\event;

use domain\shared\value\ID;
use domain\shared\value\PersonName;

class CustomerNameChanged implements Event
{
    public readonly ID $customerId;
    public readonly PersonName $name;

    private function __construct(ID $customerId, PersonName $name)
    {
        $this->customerId = $customerId;
        $this->name = $name;
    }

    public static function build(ID $customerId, PersonName $name): CustomerNameChanged
    {
        return new static($customerId, $name);
    }
}