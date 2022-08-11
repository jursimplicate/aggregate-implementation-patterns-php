<?php

namespace domain\shared\value;

final class EmailAddress
{
    public readonly string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function build(string $emailAddress): EmailAddress {
        return new EmailAddress($emailAddress);
    }

    public function equals(EmailAddress $o): bool
    {
        if ($this === $o) return true;
        return $this->value === $o->value;
    }
}