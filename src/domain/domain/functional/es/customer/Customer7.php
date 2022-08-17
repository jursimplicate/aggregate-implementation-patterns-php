<?php

namespace domain\domain\functional\es\customer;

use domain\shared\command\ChangeCustomerEmailAddress;
use domain\shared\command\ConfirmCustomerEmailAddress;
use domain\shared\command\RegisterCustomer;
use domain\shared\event\CustomerEmailAddressChanged;
use domain\shared\event\CustomerEmailAddressConfirmationFailed;
use domain\shared\event\CustomerEmailAddressConfirmed;
use domain\shared\event\CustomerRegistered;

class Customer7
{
    public static function register(RegisterCustomer $command): ?CustomerRegistered
    {
        return CustomerRegistered::build(
            $command->customerID,
            $command->emailAddress,
            $command->confirmationHash,
            $command->name
        );
    }

    public static function confirmEmailAddress(CustomerState $current, ConfirmCustomerEmailAddress $command): array
    {
        if (! $current->confirmationHash->equals($command->confirmationHash)) {
            return [CustomerEmailAddressConfirmationFailed::build($command->customerID)];
        }

        if ($current->isEmailAddressConfirmed) {
            return [];
        }

        return [CustomerEmailAddressConfirmed::build($command->customerID)];
    }

    public static function changeEmailAddress(CustomerState $current, ChangeCustomerEmailAddress $command): array
    {
        if ($current->emailAddress->equals($command->emailAddress)) {
            return [];
        }

        return [
            CustomerEmailAddressChanged::build(
                $command->customerID,
                $command->emailAddress,
                $command->confirmationHash
            )
        ];
    }
}