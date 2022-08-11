<?php

namespace domain\shared\value;

use Ramsey\Uuid\Uuid;

class Hash
{
    public readonly string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function generate(): Hash
    {
        return new Hash(Uuid::getFactory()->uuid4());
    }

    public static function build(string $hash): Hash
    {
        return new Hash($hash);
    }

    public function equals(Hash $other): bool
    {
        if ($this === $other) {
            return true;
        }
        return $this->value === $other->value;
    }
}