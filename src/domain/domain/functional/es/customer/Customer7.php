<?php

namespace domain\domain\functional\es\customer;

use domain\shared\command\ChangeCustomerEmailAddress;
use domain\shared\command\ConfirmCustomerEmailAddress;
use domain\shared\command\RegisterCustomer;
use domain\shared\event\CustomerEmailAddressChanged;
use domain\shared\event\CustomerEmailAddressConfirmed;
use domain\shared\event\CustomerRegistered;

class Customer7
{
    public static function register(RegisterCustomer $command): ?CustomerRegistered
    {
        return null; // TODO
    }

    public static function confirmEmailAddress(CustomerState $current, ConfirmCustomerEmailAddress $command): array
    {
        // TODO

        return []; // TODO
    }

    public static function changeEmailAddress(CustomerState $current, ChangeCustomerEmailAddress $command): array
    {
        // TODO

        return []; // TODO
    }
}