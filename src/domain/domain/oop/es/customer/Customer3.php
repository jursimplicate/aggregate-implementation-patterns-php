<?php

namespace domain\domain\oop\es\customer;

use domain\shared\command\ChangeCustomerEmailAddress;
use domain\shared\command\ConfirmCustomerEmailAddress;
use domain\shared\command\RegisterCustomer;
use domain\shared\event\CustomerEmailAddressChanged;
use domain\shared\event\CustomerEmailAddressConfirmationFailed;
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
    private bool $isEmailAdressConfirmed = false;
    private ?PersonName $name;

    private function __construct()
    {
    }

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
        if (!$command->confirmationHash->equals($this->confirmationHash)) {
            return [CustomerEmailAddressConfirmationFailed::build($command->customerID)];
        }
        if ($this->isEmailAdressConfirmed) {
            return [];
        }

        return [CustomerEmailAddressConfirmed::build($command->customerID)];
    }

    /**
     * @param ConfirmCustomerEmailAddress $command
     *
     * @return array<Event>
     */
    public function changeEmailAddress(ChangeCustomerEmailAddress $command): array
    {
        if ($this->emailAddress->equals($command->emailAddress)) {
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
            $this->confirmationHash = $event->confirmationHash;
            $this->emailAddress = $event->emailAddress;
            $this->name = $event->name;
        } elseif ($event instanceof CustomerEmailAddressConfirmed) {
            $this->isEmailAdressConfirmed = true;
        } elseif ($event instanceof CustomerEmailAddressChanged) {
            $this->emailAddress = $event->emailAddress;
            $this->confirmationHash = $event->confirmationHash;
            $this->isEmailAdressConfirmed = false;
        }
    }
}