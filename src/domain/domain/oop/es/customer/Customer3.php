<?php

namespace domain\domain\oop\es\customer;

use domain\shared\command\ChangeCustomerEmailAddress;
use domain\shared\command\ConfirmCustomerEmailAddress;
use domain\shared\command\RegisterCustomer;
use domain\shared\event\CustomerEmailAddressChanged;
use domain\shared\event\CustomerEmailAddressConfirmed;
use domain\shared\event\CustomerRegistered;
use domain\shared\event\Event;
use domain\shared\value\EmailAddress;
use domain\shared\value\Hash;
use domain\shared\value\PersonName;

class Customer3
{
    private ?EmailAddress $emailAddress;
    private ?Hash $confirmationHash;
    private bool $isEmailAdressConfirmed;
    private ?PersonName $name;

    private function __construct()
    {
    }

    public static function register(RegisterCustomer $command): ?CustomerRegistered
    {
        // TODO
        return null;
    }

    /**
     * @param array<Event> $events
     */
    public static function reconstitute(array $events): Customer3
    {
        $customer = new Customer3();

        $customer->apply($events);

        return $customer;
    }

    /**
     * @param ConfirmCustomerEmailAddress $command
     *
     * @return array<Event>
     */
    public function confirmEmailAddress(ConfirmCustomerEmailAddress $command): array
    {
        // TODO

        return [];
    }

    /**
     * @param ConfirmCustomerEmailAddress $command
     *
     * @return array<Event>
     */
    public function changeEmailAddress(ChangeCustomerEmailAddress $command): array
    {
        // TODO

        return [];
    }

    /**
     * @param array<Event> $events
     */
    private function apply(array $events)
    {
        foreach ($events as $event) {
            $this->applyEvent($event);
        }
    }

    private function applyEvent(Event $event): void
    {
        if ($event instanceof CustomerRegistered) {
            // TODO
        }
        elseif ($event instanceof CustomerEmailAddressConfirmed) {
            // TODO
        }
        elseif ($event instanceof CustomerEmailAddressChanged) {
            // TODO
        }
    }
}