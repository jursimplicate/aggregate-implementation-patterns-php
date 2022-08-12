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

class Customer4
{
    private ?EmailAddress $emailAddress;
    private ?Hash $confirmationHash;
    private bool $isEmailAdressConfirmed;
    private ?PersonName $name;

    private array $recordedEvents;

    private function __construct()
    {
        $this->recordedEvents = [];
    }

    public static function register(RegisterCustomer $command): Customer4
    {
        $customer = new Customer4();

        // TODO

        return $customer;
    }

    /**
     * @param array<Event> $events
     */
    public static function reconstitute(array $events): Customer4
    {
        $customer = new Customer4();

        $customer->apply($events);

        return $customer;
    }

    public function confirmEmailAddress(ConfirmCustomerEmailAddress $command): void
    {
        // TODO
    }

    public function changeEmailAddress(ChangeCustomerEmailAddress $command): void
    {
        // TODO
    }

    /**
     * @return array<Event>
     */
    public function getRecordedEvents(): array
    {
        return $this->recordedEvents;
    }

    private function recordThat(Event $event): void
    {
        $this->recordedEvents[] = $event;
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