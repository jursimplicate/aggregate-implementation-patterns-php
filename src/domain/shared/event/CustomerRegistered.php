<?php

namespace domain\shared\event;

use domain\shared\value\EmailAddress;
use domain\shared\value\Hash;
use domain\shared\value\ID;
use domain\shared\value\PersonName;

class CustomerRegistered implements Event
{
    public readonly ID $customerID;
    public readonly EmailAddress $emailAddress;
    public readonly Hash $confirmationHash;
    public readonly PersonName $name;

    private function __construct(ID $customerID, EmailAddress $emailAddress, Hash $confirmationHash, PersonName $name)
    {
        $this->customerID = $customerID;
        $this->emailAddress = $emailAddress;
        $this->confirmationHash = $confirmationHash;
        $this->name = $name;
    }

    public static function build(ID $customerID, EmailAddress $emailAddress, Hash $confirmationHash, PersonName $name): CustomerRegistered
    {
        return new static($customerID, $emailAddress, $confirmationHash, $name);
    }

}