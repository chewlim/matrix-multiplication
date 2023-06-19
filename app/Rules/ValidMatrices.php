<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidMatrices implements DataAwareRule, ValidationRule
{
    /**
     * All of the data under validation.
     *
     * @var array<string, mixed>
     */
    protected $data = [];

    /**
     * Set the data under validation.
     *
     * @param  array<string, mixed>  $data
     */
    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Get first row of the Matrix A then count the column
        $columnCountOfMatrixA = count(collect($this->data['matrix_a'])->first());

        // Make sure every row has the same column count
        collect(['matrix_a', 'matrix_b'])
            ->each(function ($key) use ($fail) {
                $columnCount = count(collect($this->data[$key])->first());

                $hasSameColumnCount = collect($this->data[$key])->every(function ($value) use ($columnCount) {
                    return count($value) === $columnCount;
                });

                if (!$hasSameColumnCount) {
                    $fail("Every row in " . str($key)->headline() . " must have the same column count.");
                }
            });

        // The column count in the first matrix should be equal to the row count of the second matrix
        $rowCountOfMatrixB = count($this->data['matrix_b']);
        if ($columnCountOfMatrixA !== $rowCountOfMatrixB) {
            $fail("The column count in Matrix A (Current value: {$columnCountOfMatrixA}) must be equal to the row count of Matrix B (Current value: {$rowCountOfMatrixB}).");
        }
    }
}
