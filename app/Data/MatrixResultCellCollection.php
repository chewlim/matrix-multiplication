<?php

namespace App\Data;

use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;
use TypeError;

class MatrixResultCellCollection implements ArrayAccess, IteratorAggregate
{
    public function __construct(private array $cells = [])
    {
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->cells[$offset]);
    }

    public function offsetGet(mixed $offset): MatrixResultCell
    {
        return $this->cells[$offset];
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($value instanceof MatrixResultCell) {
            $this->cells[$offset] = $value;
        } else {
            throw new TypeError("Not a MatrixResultCell!");
        }
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->cells[$offset]);
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->cells);
    }
}
