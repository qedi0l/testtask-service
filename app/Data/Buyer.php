<?php

namespace App\Data;

use App\Contracts\BuyerInterface;

class Buyer implements BuyerInterface
{
    private array $attributes;

    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;

    }

    public function offsetExists($offset): bool
    {
        return isset($this->attributes[$offset]);
    }

    public function offsetGet($offset): mixed
    {
        return $this->attributes[$offset] ?? null;
    }

    public function offsetSet($offset, $value): void
    {
        if (is_null($offset)) {
            $this->attributes[] = $value;
        } else {
            $this->attributes[$offset] = $value;
        }
    }

    public function offsetUnset($offset): void
    {
        unset($this->attributes[$offset]);
    }

    public function __get($name)
    {
        return $this->attributes[$name] ?? null;
    }

    public function __set($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    public function __isset($name): bool
    {
        return isset($this->attributes[$name]);
    }

    public function __unset($name): void
    {
        unset($this->attributes[$name]);
    }

}
