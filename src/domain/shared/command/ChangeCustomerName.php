<?php

namespace domain\shared\command;

use domain\shared\value\ID;
use domain\shared\value\PersonName;

final class ChangeCustomerName
{
    public readonly ID $customerId;
    public readonly PersonName $name;

    private function __construct(string $customerId, string $givenName, string $familyName)
    {
        $this->customerId = ID::build($customerId);
        $this->name = PersonName::build($givenName, $familyName);
    }

    public static function build(string $customerId, string $givenName, string $familyName): ChangeCustomerName
    {
        return new static($customerId, $givenName, $familyName);
    }
}