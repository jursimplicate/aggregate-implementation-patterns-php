<?php

namespace domain\domain\functional\es\customer;

use domain\shared\command\ChangeCustomerEmailAddress;
use domain\shared\command\ConfirmCustomerEmailAddress;
use domain\shared\command\RegisterCustomer;
use domain\shared\event\CustomerEmailAddressChanged;
use domain\shared\event\CustomerEmailAddressConfirmationFailed;
use domain\shared\event\CustomerEmailAddressConfirmed;
use domain\shared\event\CustomerRegistered;
use domain\shared\event\Event;

class Customer6
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

    /**
     * @param array<Event> $eventStream
     * @param ConfirmCustomerEmailAddress $command
     *
     * @return array<Event>
     */
    public static function confirmEmailAddress(array $eventStream, ConfirmCustomerEmailAddress $command): array
    {
        $current = CustomerState::reconstitute($eventStream);

        if (! $current->confirmationHash->equals($command->confirmationHash)) {
            return [CustomerEmailAddressConfirmationFailed::build($command->customerID)];
        }

        if ($current->isEmailAddressConfirmed) {
            return [];
        }

        return [CustomerEmailAddressConfirmed::build($command->customerID)];
    }

    /**
     * @param array<Event> $eventStream
     * @param ChangeCustomerEmailAddress $command
     *
     * @return array<Event>
     */
    public static function changeEmailAddress(array $eventStream, ChangeCustomerEmailAddress $command): array
    {
        $current = CustomerState::reconstitute($eventStream);

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