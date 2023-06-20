<?php

namespace App\Data;

use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;
use TypeError;

class MatrixResultRowCollection implements ArrayAccess, IteratorAggregate
{
    public function __construct(private array $rows)
    {
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->rows[$offset]);
    }

    public function offsetGet(mixed $offset): MatrixResultRow
    {
        return $this->rows[$offset];
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($value instanceof MatrixResultRow) {
            $this->rows[$offset] = $value;
        } else {
            throw new TypeError("Not a MatrixResultRow!");
        }
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->rows[$offset]);
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->rows);
    }

    public function toArray(): array
    {
        return collect($this->rows)->map(function (MatrixResultRow $row) {
            return collect($row->cells())->map(function (MatrixResultCell $cell) {
                return $cell->toArray();
            })->all();
        })->all();
    }
}
