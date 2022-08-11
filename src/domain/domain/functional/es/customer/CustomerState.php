<?php

namespace domain\domain\functional\es\customer;

use domain\shared\event\CustomerEmailAddressChanged;
use domain\shared\event\CustomerEmailAddressConfirmed;
use domain\shared\event\CustomerRegistered;
use domain\shared\event\Event;
use domain\shared\value\EmailAddress;
use domain\shared\value\Hash;
use domain\shared\value\PersonName;

class CustomerState
{
    public EmailAddress $emailAddress;
    public Hash $confirmationHash;
    public PersonName $name;
    public bool $isEmailAddressConfirmed;

    private function __construct()
    {
    }

    /**
     * @param array<Event> $events
     *
     * @return CustomerState
     */
    public static function reconstitute(array $events): CustomerState
    {
        $customer = new CustomerState();

        $customer->apply($events);

        return $customer;
    }

    /**
     * @param array<Event> $events
     *
     * @return void
     */
    private function apply(array $events): void
    {
        foreach ($events as $event)  {
            if ($event instanceof CustomerRegistered) {
                // TODO
                continue;
            }

            if ($event instanceof CustomerEmailAddressConfirmed) {
                // TODO
                continue;
            }

            if ($event instanceof CustomerEmailAddressChanged) {
                // TODO
            }
        }
    }

}