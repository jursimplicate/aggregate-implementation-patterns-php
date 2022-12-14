<?php

namespace domain\domain\functional\es\customer;

use domain\shared\command\ChangeCustomerEmailAddress;
use domain\shared\command\ConfirmCustomerEmailAddress;
use domain\shared\command\RegisterCustomer;
use domain\shared\event\CustomerEmailAddressChanged;
use domain\shared\event\CustomerEmailAddressConfirmed;
use domain\shared\event\CustomerRegistered;
use domain\shared\event\Event;

class Customer6
{
    public static function register(RegisterCustomer $command): ?CustomerRegistered
    {
        return null; // TODO
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

        // TODO

        return [];
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

        // TODO

        return [];
    }
}