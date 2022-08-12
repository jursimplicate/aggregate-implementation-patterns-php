<?php

namespace Unit\domain\oop\es\customer;

use domain\domain\oop\es\customer\Customer3;
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

class Customer3Test extends TestCase
{
    private ?ID $customerID;
    private ?EmailAddress $emailAddress;
    private ?EmailAddress $changedEmailAddress;
    private ?Hash $confirmationHash;
    private ?Hash $wrongConfirmationHash;
    private ?Hash $changedConfirmationHash;
    private ?PersonName $name;
    private ?CustomerRegistered $customerRegistered;
    /** @var array<Event>  */
    private array $recordedEvents;
    private ?Customer3 $registeredCustomer;

    protected function setUp(): void
    {
        $this->customerID = ID::generate();
        $this->emailAddress = EmailAddress::build("john@doe.com");
        $this->changedEmailAddress = EmailAddress::build("john+changed@doe.com");
        $this->confirmationHash = Hash::generate();
        $this->wrongConfirmationHash = Hash::generate();
        $this->changedConfirmationHash = Hash::generate();
        $this->name = PersonName::build("John", "Doe");
        $this->recordedEvents = [];
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
     * @depends confirmEmailAddress
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

    private function WHEN_RegisterCustomer()
    {
        $this->registerCustomer = RegisterCustomer::build($this->emailAddress->value, $this->name->givenName, $this->name->familyName);
        $this->customerRegistered = Customer3::register($this->registerCustomer);
        $this->customerID = $this->registerCustomer->customerID;
        $this->confirmationHash = $this->registerCustomer->confirmationHash;
    }

    private function THEN_CustomerRegistered()
    {
        $method = "register";
        $eventName = "CustomerRegistered";
        self::assertNotNull($this->customerRegistered, THelper::eventIsNull("register", $eventName));
        self::assertEquals($this->customerID, $this->customerRegistered->customerID, THelper::propertyIsWrong($method, "customerID"));
        self::assertEquals($this->emailAddress, $this->customerRegistered->emailAddress, THelper::propertyIsWrong($method, "emailAddress"));
        self::assertEquals($this->confirmationHash, $this->customerRegistered->confirmationHash, THelper::propertyIsWrong($method, "confirmationHash"));
        self::assertEquals($this->name, $this->customerRegistered->name, THelper::propertyIsWrong($method, "name"));
    }

    private function GIVEN(Event... $events)
    {
        $this->registeredCustomer = Customer3::reconstitute($events);
    }

    private function customerIsRegistered()
    {
        return CustomerRegistered::build($this->customerID, $this->emailAddress, $this->confirmationHash, $this->name);
    }

    private function WHEN_ConfirmEmailAddress_With(?Hash $confirmationHash)
    {
        $command = ConfirmCustomerEmailAddress::build($this->customerID->value, $confirmationHash->value);
        try {
            $this->recordedEvents = $this->registeredCustomer->confirmEmailAddress($command);
        } catch (\Exception $e) {
        self::fail(THelper::propertyIsNull("confirmationHash"));
    }
    }

    private function THEN_EmailAddressConfirmed()
    {
        $method = "confirmEmailAddress";
        $eventName = "CustomerEmailAddressConfirmed";
        self::assertEquals(1, count($this->recordedEvents), THelper::noEventWasRecorded($method, $eventName));
        /** @var CustomerEmailAddressConfirmed $event */
        $event = $this->recordedEvents[0];
        self::assertNotNull($event, THelper::eventIsNull($method, $eventName));
        self::assertEquals(CustomerEmailAddressConfirmed::class, get_class($event), THelper::eventOfWrongTypeWasRecorded($method));
        self::assertEquals($this->customerID, $event->customerID, THelper::propertyIsWrong($method, "customerID"));

    }

    private function THEN_EmailAddressConfirmationFailed()
    {
        $method = "confirmEmailAddress";
        $eventName = "CustomerEmailAddressConfirmationFailed";
        self::assertEquals(1, count($this->recordedEvents), THelper::noEventWasRecorded($method, $eventName));
        /** @var CustomerEmailAddressConfirmationFailed $event */
        $event = $this->recordedEvents[0];
        self::assertNotNull($event, THelper::eventIsNull($method, $eventName));
        self::assertEquals(CustomerEmailAddressConfirmationFailed::class, get_class($event), THelper::eventOfWrongTypeWasRecorded($method));
        self::assertEquals($this->customerID, $event->customerID, THelper::propertyIsWrong($method, "customerID"));
    }

    private function __and_EmailAddressWasConfirmed()
    {
        return CustomerEmailAddressConfirmed::build($this->customerID);
    }

    private function THEN_NothingShouldHappen()
    {
        self::assertEquals(0, count($this->recordedEvents), THelper::noEventShouldHaveBeenRecorded(THelper::typeOfFirst($this->recordedEvents)));
    }

    private function WHEN_ChangeEmailAddress_With(?EmailAddress $emailAddress)
    {
        $command = ChangeCustomerEmailAddress::build($this->customerID->value, $emailAddress->value);
        try {
            $this->recordedEvents = $this->registeredCustomer->changeEmailAddress($command);
        } catch (\Exception $e) {
            self::fail(THelper::propertyIsNull("emailAddress"));
        }
    }

    private function THEN_EmailAddressChanged()
    {
        $method = "changeEmailAddress";
        $eventName = "CustomerEmailAddressChanged";
        self::assertEquals(1, count($this->recordedEvents), THelper::noEventWasRecorded($method, $eventName));
        /** @var CustomerEmailAddressChanged $event */
        $event = $this->recordedEvents[0];
        self::assertNotNull($event, THelper::eventIsNull($method, $eventName));
        self::assertEquals(CustomerEmailAddressChanged::class, get_class($event), THelper::eventOfWrongTypeWasRecorded($method));
        self::assertEquals($this->customerID, $event->customerID, THelper::propertyIsWrong($method, "customerID"));
        self::assertEquals($this->changedEmailAddress, $event->emailAddress, THelper::propertyIsWrong($method, "emailAddress"));
    }

    private function __and_EmailAddressWasChanged(): CustomerEmailAddressChanged
    {
        return CustomerEmailAddressChanged::build($this->customerID, $this->changedEmailAddress, $this->changedConfirmationHash);
    }
}