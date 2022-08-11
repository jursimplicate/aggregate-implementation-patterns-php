<?php

namespace domain\shared\command;

use domain\shared\value\Hash;
use domain\shared\value\ID;

class ConfirmCustomerEmailAddress
{
    public readonly ID $customerID;
    public readonly Hash $confirmationHash;

    public function __construct(string $customerID, string $confirmationHash)
    {
        $this->customerID = ID::build($customerID);
        $this->confirmationHash = Hash::build($confirmationHash);
    }

    public static function build(string $customerID, string $confirmationHash): ConfirmCustomerEmailAddress
    {
        return new static($customerID, $confirmationHash);
    }
}