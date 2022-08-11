<?php

namespace domain\shared\event;

use domain\shared\value\ID;

class CustomerEmailAddressConfirmationFailed implements Event
{
    public readonly ID $customerID;

    private function __construct(ID $customerID)
    {
        $this->customerID = $customerID;
    }

    public static function build(ID $customerID): CustomerEmailAddressConfirmationFailed
    {
        return new static($customerID);
    }
}