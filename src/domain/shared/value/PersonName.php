<?php

namespace domain\shared\value;

class PersonName
{
    public readonly string $givenName;
    public readonly string $familyName;

    public function __construct(string $givenName, string $familyName)
    {
        $this->givenName = $givenName;
        $this->familyName = $familyName;
    }

    public static function build(string $givenName, string $familyName): PersonName
    {
        return new PersonName($givenName, $familyName);
    }

    public function equals(PersonName $other): bool
    {
        if ($this === $other) {
            return true;
        }
        return $this->givenName === $other->givenName && $this->familyName === $other->familyName;
    }
}