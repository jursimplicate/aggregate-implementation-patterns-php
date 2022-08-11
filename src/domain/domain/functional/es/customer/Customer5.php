<?php

namespace domain\domain\functional\es\customer;

use domain\shared\command\ChangeCustomerEmailAddress;
use domain\shared\command\ConfirmCustomerEmailAddress;
use domain\shared\command\RegisterCustomer;
use domain\shared\event\CustomerEmailAddressChanged;
use domain\shared\event\CustomerEmailAddressConfirmed;
use domain\shared\event\CustomerRegistered;
use domain\shared\event\Event;

class Customer5
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
        $isEmailAddressConfirmed = false;
        $confirmationHash = null;

        foreach ($eventStream as $event) {
            if ($event instanceof CustomerRegistered) {
                // TODO
            } else if ($event instanceof CustomerEmailAddressConfirmed) {
                // TODO
            } else if ($event instanceof CustomerEmailAddressChanged) {
                // TODO
            }
        }

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
        $emailAddress = null;

        foreach ($eventStream as $event) {
            if ($event instanceof CustomerRegistered) {
                // TODO
            } else if ($event instanceof CustomerEmailAddressChanged) {
                // TODO
            }
        }

        // TODO

        return [];
    }
}