<?php

namespace App\Data;

class MatrixResultCell
{
    public string $characters;

    public function __construct(public int $value)
    {
        $this->characters = $this->getCharacters($value);
    }

    /**
     * Convert the value to character similar to excel columns. e.g 1 => A, 26 => Z, 27 => AA, 28 => AB
     *
     * @param int $value The number to be converted.
     *
     * @return string
     */
    protected function getCharacters(int $value): string
    {
        // ASCII 65 = A
        $balance = ($value - 1) % 26; // e.g (32 - 1) % 26  = 5

        $suffixLetter = chr(65 + $balance); // e.g 65 + 5 = F

        $cycle = intval(($value - 1) / 26); // e.g. (32 - 1) / 26 = 1.19 => 1

        if ($cycle > 0) {
            return $this->getCharacters($cycle) . $suffixLetter;
        }

        return $suffixLetter;
    }

    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'characters' => $this->characters,
        ];
    }
}
