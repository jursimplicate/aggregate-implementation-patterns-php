<?php

namespace domain\shared\command;

use domain\shared\value\EmailAddress;
use domain\shared\value\Hash;
use domain\shared\value\ID;

final class ChangeCustomerEmailAddress
{
    public readonly ID $customerID;
    public readonly EmailAddress $emailAddress;
    public readonly Hash $confirmationHash;

    private function __construct(string $customerId, string $emailAddress)
    {
        $this->customerID = ID::build($customerId);
        $this->emailAddress = EmailAddress::build($emailAddress);
        $this->confirmationHash = Hash::generate();
    }

    public static function build(string $customerId, string $emailAddress): ChangeCustomerEmailAddress
    {
        return new static($customerId, $emailAddress);
    }

}