<?php

namespace domain\shared\event;

use domain\shared\value\EmailAddress;
use domain\shared\value\Hash;
use domain\shared\value\ID;

class CustomerEmailAddressChanged implements Event
{
    public readonly ID $customerId;
    public readonly EmailAddress $emailAddress;
    public readonly Hash $confirmationHash;

    private function __construct(ID $customerId, EmailAddress $emailAddress, Hash $confirmationHash)
    {
        $this->customerId = $customerId;
        $this->emailAddress = $emailAddress;
        $this->confirmationHash = $confirmationHash;
    }

    public static function build(ID $customerId, EmailAddress $emailAddress, Hash $confirmationHash): CustomerEmailAddressChanged
    {
        return new static($customerId, $emailAddress, $confirmationHash);
    }
}