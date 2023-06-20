<?php

namespace App\Data;

class MatrixResultRow
{
    public function __construct(protected MatrixResultCellCollection $cells)
    {
        $this->cells = $cells;
    }

    public function cells(): MatrixResultCellCollection
    {
        return $this->cells;
    }
}
