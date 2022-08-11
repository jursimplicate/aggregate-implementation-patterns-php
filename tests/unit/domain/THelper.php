<?php

namespace unit\domain;

class THelper
{
    public static function typeOfFirst(array $recordedEvents): string
    {
        if (count($recordedEvents) === 0) {
            return "???";
        }

        return get_class($recordedEvents[0]);
    }

    public static function propertyIsNull(string $property): string
    {
        return sprintf(
                "PROBLEM: The %s is null!\n" .
                        "HINT: Maybe you didn't apply the previous events properly!?\n",
                $property
        );
    }

    public static function eventIsNull(string $method, string $expectedEvent): string
    {
        return sprintf(
                "PROBLEM in %s(): The recorded/returned event is NULL!\n" .
                        "HINT: Make sure you record/return a %s event\n\n",
                $method,
                $expectedEvent
        );
    }

    public static function propertyIsWrong(string $method, string $property): string
    {
        return sprintf(
                "PROBLEM in %s(): The event contains a wrong %s!\n" .
                        "HINT: The %s in the event should be taken from the command!\n\n",
                $method, $property, $property
        );
    }

    public static function noEventWasRecorded(string $method, string $expectedEvent): string
    {
        return sprintf(
                "PROBLEM in %s(): No event was recorded/returned!\n" .
                        "HINTS: Build a %s event and record/return it!\n" .
                        "       Did you apply all previous events properly?\n" .
                        "       Check your business logic :-)!\n\n",
                $method, $expectedEvent
        );
    }

    public static function eventOfWrongTypeWasRecorded(string $method): string
    {
        return sprintf(
                "PROBLEM in %s(): An event of the wrong type was recorded/returned!\n" .
                        "HINTS: Did you apply all previous events properly?\n" .
                        "       Check your business logic :-)!\n\n",
                $method
        );
    }

    public static function noEventShouldHaveBeenRecorded(string $recordedEventType): string
    {
        return sprintf(
                "PROBLEM: No event should have been recorded/returned!\n" .
                        "HINTS: Check your business logic - this command should be ignored (idempotency)!\n" .
                        "       Did you apply all previous events properly?\n" .
                        "       The recorded/returned event is of type %s.\n\n",
                $recordedEventType
        );
    }
}