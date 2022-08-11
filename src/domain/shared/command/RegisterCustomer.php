<?php

namespace domain\shared\command;

use domain\shared\value\EmailAddress;
use domain\shared\value\Hash;
use domain\shared\value\ID;
use domain\shared\value\PersonName;

class RegisterCustomer
{
    public readonly ID $customerID;
    public readonly EmailAddress $emailAddress;
    public readonly Hash $confirmationHash;
    public readonly PersonName $name;

    public function __construct(string $emailAddress, string $givenName, string $familyName)
    {
        $this->customerID = ID::generate();
        $this->confirmationHash = Hash::generate();
        $this->emailAddress = EmailAddress::build($emailAddress);
        $this->name = PersonName::build($givenName, $familyName);
    }

    public static function build(string $emailAddress, string $givenName, string $familyName): RegisterCustomer
    {
        return new static($emailAddress, $givenName, $familyName);
    }
}