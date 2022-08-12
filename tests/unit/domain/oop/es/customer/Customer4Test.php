<?php

namespace unit\domain\oop\es\customer;

use domain\domain\oop\es\customer\Customer3;
use domain\domain\oop\es\customer\Customer4;
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
use domain\shared\value\ID;
use domain\shared\value\PersonName;
use PHPUnit\Framework\TestCase;
use Unit\domain\THelper;

class Customer4Test extends TestCase
{
    private ?ID $customerID;
    private ?EmailAddress $emailAddress;
    private ?EmailAddress $changedEmailAddress;
    private ?Hash $confirmationHash;
    private ?Hash $wrongConfirmationHash;
    private ?Hash $changedConfirmationHash;
    private ?PersonName $name;
    private ?Customer4 $registeredCustomer;

    protected function setUp(): void
    {
        $this->customerID = ID::generate();
        $this->emailAddress = EmailAddress::build("john@doe.com");
        $this->changedEmailAddress = EmailAddress::build("john+changed@doe.com");
        $this->confirmationHash = Hash::generate();
        $this->wrongConfirmationHash = Hash::generate();
        $this->changedConfirmationHash = Hash::generate();
        $this->name = PersonName::build("John", "Doe");
    }

    /** @test */
    public function registerCustomer() {
        $this->WHEN_RegisterCustomer();
        $this->THEN_CustomerRegistered();
    }

    /**
     * @test
     * @depends registerCustomer
     */
    public function confirmEmailAddress(): void
    {
        $this->GIVEN($this->customerIsRegistered());
        $this->WHEN_ConfirmEmailAddress_With($this->confirmationHash);
        $this->THEN_EmailAddressConfirmed();
    }

    /**
     * @test
     * @depends confirmEmailAddress
     */
    public function confirmEmailAddress_withWrongConfirmationHash(): void
    {
        $this->GIVEN($this->customerIsRegistered());
        $this->WHEN_ConfirmEmailAddress_With($this->wrongConfirmationHash);
        $this->THEN_EmailAddressConfirmationFailed();
    }

    /**
     * @test
     * @depends confirmEmailAddress_withWrongConfirmationHash
     */
    public function confirmEmailAddress_whenItWasAlreadyConfirmed(): void
    {
        $this->GIVEN($this->customerIsRegistered(),
            $this->__and_EmailAddressWasConfirmed());
        $this->WHEN_ConfirmEmailAddress_With($this->confirmationHash);
        $this->THEN_NothingShouldHappen();
    }

    /**
     * @test
     * @depends confirmEmailAddress_whenItWasAlreadyConfirmed
     */
    public function confirmEmailAddress_withWrongConfirmationHash_whenItWasAlreadyConfirmed(): void
    {
        $this->GIVEN($this->customerIsRegistered(),
            $this->__and_EmailAddressWasConfirmed());
        $this->WHEN_ConfirmEmailAddress_With($this->wrongConfirmationHash);
        $this->THEN_EmailAddressConfirmationFailed();
    }

    /**
     * @test
     * @depends confirmEmailAddress_withWrongConfirmationHash_whenItWasAlreadyConfirmed
     */
    public function changeEmailAddress(): void
    {
        $this->GIVEN($this->customerIsRegistered());
        $this->WHEN_ChangeEmailAddress_With($this->changedEmailAddress);
        $this->THEN_EmailAddressChanged();
    }

    /**
     * @test
     * @depends changeEmailAddress
     */
    public function changeEmailAddress_withUnchangedEmailAddress(): void
    {
        $this->GIVEN($this->customerIsRegistered());
        $this->WHEN_ChangeEmailAddress_With($this->emailAddress);
        $this->THEN_NothingShouldHappen();
    }

    /**
     * @test
     * @depends changeEmailAddress_withUnchangedEmailAddress
     */
    public function changeEmailAddress_whenItWasAlreadyChanged(): void
    {
        $this->GIVEN($this->customerIsRegistered(),
            $this->__and_EmailAddressWasChanged());
        $this->WHEN_ChangeEmailAddress_With($this->changedEmailAddress);
        $this->THEN_NothingShouldHappen();
    }

    /**
     * @test
     * @depends changeEmailAddress_whenItWasAlreadyChanged
     */
    public function confirmEmailAddress_whenItWasPreviouslyConfirmedAndThenChanged(): void
    {
        $this->GIVEN($this->customerIsRegistered(),
            $this->__and_EmailAddressWasConfirmed(),
            $this->__and_EmailAddressWasChanged());
        $this->WHEN_ConfirmEmailAddress_With($this->changedConfirmationHash);
        $this->THEN_EmailAddressConfirmed();
    }

    /**
     * Methods for GIVEN
     */

    private function GIVEN(Event... $events): void
    {
        $this->registeredCustomer = Customer4::reconstitute($events);
    }

    private function customerIsRegistered(): CustomerRegistered
    {
        return CustomerRegistered::build($this->customerID, $this->emailAddress, $this->confirmationHash, $this->name);
    }

    private function __and_EmailAddressWasConfirmed(): CustomerEmailAddressConfirmed
    {
        return CustomerEmailAddressConfirmed::build($this->customerID);
    }

    private function __and_EmailAddressWasChanged(): CustomerEmailAddressChanged
    {
        return CustomerEmailAddressChanged::build($this->customerID, $this->changedEmailAddress, $this->changedConfirmationHash);
    }

    /**
     * Methods for WHEN
     */

    private function WHEN_RegisterCustomer()
    {
        $registerCustomer = RegisterCustomer::build($this->emailAddress->value, $this->name->givenName, $this->name->familyName);
        $this->registeredCustomer= Customer4::register($registerCustomer);
        $this->customerID = $registerCustomer->customerID;
        $this->confirmationHash = $registerCustomer->confirmationHash;
    }

    private function WHEN_ConfirmEmailAddress_With(?Hash $confirmationHash): void
    {
        $command = ConfirmCustomerEmailAddress::build($this->customerID->value, $confirmationHash->value);
        try {
            $this->registeredCustomer->confirmEmailAddress($command);
        } catch (\Exception $e) {
            self::fail(THelper::propertyIsNull("confirmationHash"));
        }
    }

    private function WHEN_ChangeEmailAddress_With(?EmailAddress $emailAddress)
    {
        $command = ChangeCustomerEmailAddress::build($this->customerID->value, $emailAddress->value);
        try {
            $this->registeredCustomer->changeEmailAddress($command);
        } catch (\Exception $e) {
            self::fail(THelper::propertyIsNull("emailAddress"));
        }
    }
    /**
     * Methods for THEN
     */

    private function THEN_CustomerRegistered()
    {
        $method = "register";
        $eventName = "CustomerRegistered";
        $recordedEvents = $this->registeredCustomer->getRecordedEvents();
        self::assertEquals(1, count($recordedEvents), THelper::noEventWasRecorded($method, $eventName));
        /** @$CustomerRegistered $event */
        $event = $recordedEvents[0];
        self::assertNotNull($event, THelper::eventIsNull($method, $eventName));
        self::assertEquals(CustomerRegistered::class, get_class($event), THelper::eventOfWrongTypeWasRecorded($method));
        self::assertEquals($this->customerID, $event->customerID, THelper::propertyIsWrong($method, "customerID"));
        self::assertEquals($this->emailAddress, $event->emailAddress, THelper::propertyIsWrong($method, "emailAddress"));
        self::assertEquals($this->confirmationHash, $event->confirmationHash, THelper::propertyIsWrong($method, "confirmationHash"));
        self::assertEquals($this->name, $event->name, THelper::propertyIsWrong($method, "name"));
    }

    private function THEN_EmailAddressConfirmed()
    {
        $method = "confirmEmailAddress";
        $eventName = "CustomerEmailAddressConfirmed";
        $recordedEvents = $this->registeredCustomer->getRecordedEvents();
        self::assertEquals(1, count($recordedEvents), THelper::noEventWasRecorded($method, $eventName));
        /** @var CustomerEmailAddressConfirmed $event */
        $event = $recordedEvents[0];
        self::assertNotNull($event, THelper::eventIsNull($method, $eventName));
        self::assertEquals(CustomerEmailAddressConfirmed::class, get_class($event), THelper::eventOfWrongTypeWasRecorded($method));
        self::assertEquals($this->customerID, $event->customerID, THelper::propertyIsWrong($method, "customerID"));
    }

    private function THEN_EmailAddressConfirmationFailed()
    {
        $method = "confirmEmailAddress";
        $eventName = "CustomerEmailAddressConfirmationFailed";
        $recordedEvents = $this->registeredCustomer->getRecordedEvents();
        self::assertEquals(1, count($recordedEvents), THelper::noEventWasRecorded($method, $eventName));
        /** @var CustomerEmailAddressConfirmationFailed $event */
        $event = $recordedEvents[0];
        self::assertNotNull($event, THelper::eventIsNull($method, $eventName));
        self::assertEquals(CustomerEmailAddressConfirmationFailed::class, get_class($event), THelper::eventOfWrongTypeWasRecorded($method));
        self::assertEquals($this->customerID, $event->customerID, THelper::propertyIsWrong($method, "customerID"));
    }

    private function THEN_EmailAddressChanged()
    {
        $method = "changeEmailAddress";
        $eventName = "CustomerEmailAddressChanged";
        $recordedEvents = $this->registeredCustomer->getRecordedEvents();
        self::assertEquals(1, count($recordedEvents), THelper::noEventWasRecorded($method, $eventName));
        /** @var CustomerEmailAddressChanged $event */
        $event = $recordedEvents[0];
        self::assertNotNull($event, THelper::eventIsNull($method, $eventName));
        self::assertEquals(CustomerEmailAddressChanged::class, get_class($event), THelper::eventOfWrongTypeWasRecorded($method));
        self::assertEquals($this->customerID, $event->customerID, THelper::propertyIsWrong($method, "customerID"));
        self::assertEquals($this->changedEmailAddress, $event->emailAddress, THelper::propertyIsWrong($method, "emailAddress"));
    }

    private function THEN_NothingShouldHappen()
    {
        $recordedEvents = $this->registeredCustomer->getRecordedEvents();
        self::assertEquals(0, count($this->registeredCustomer->getRecordedEvents()), THelper::noEventShouldHaveBeenRecorded(THelper::typeOfFirst($recordedEvents)));
    }
}

