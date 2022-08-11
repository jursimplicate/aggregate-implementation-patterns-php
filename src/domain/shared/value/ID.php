<?php

namespace domain\shared\value;

use Ramsey\Uuid\Uuid;

class ID
{
    public readonly string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function generate(): ID
    {
        return new ID(Uuid::getFactory()->uuid4());
    }

    public static function build(string $id): ID
    {
        return new ID($id);
    }

    public function equals(ID $other): bool
    {
        if ($this === $other) {
            return true;
        }
        return $this->value === $other->value;
    }
}