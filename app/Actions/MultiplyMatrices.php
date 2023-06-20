<?php

namespace App\Actions;

use App\Rules\ValidMatrices;
use Illuminate\Support\Facades\Validator;

class MultiplyMatrices
{
    public function __construct(protected bool $returnCharacters = false)
    {

    }

    /**
     * Multiply two matrices.
     *
     * @param array   $matrixA
     * @param array   $matrixB
     * @param bool $returnCharacters
     *
     * @return array
     */
    public function handle(array $matrixA, array $matrixB): array
    {
        $this->validate([
            'matrix_a' => $matrixA,
            'matrix_b' => $matrixB,
        ]);

        $result = [];

        $columns = count($matrixB[0]); // number of columns in matrixB

        foreach ($matrixA as $rowIndex => $currentRow) {
            for ($columnIndex = 0; $columnIndex < $columns; $columnIndex++) {
                // Transform columns in matrixB at current $index to row
                $transformed = collect($matrixB)->map(fn ($item) => $item[$columnIndex])->all();

                $result[$rowIndex][$columnIndex] = 0; // initialise

                foreach ($transformed as $pointerIndex => $item) {
                    $result[$rowIndex][$columnIndex] += $currentRow[$pointerIndex] * $item;
                }
            }
        }

        return $this->returnCharacters ? $this->transformToCharacters($result) : $result;
    }

    /**
     * Traansform the numbers to characters representation.
     *
     * @param array $data   The 2 dimensional array.
     *
     * @return array
     */
    protected function transformToCharacters(array $data): array
    {
        $result = [];
        for ($row = 0; $row < count($data); $row++) {
            for ($col = 0; $col < count($data[$row]); $col++) {
                $result[$row][$col] = $this->getCharacters($data[$row][$col]);
            }
        }
        return $result;
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

    /**
     * Get the validation rules that apply to action
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'matrix_a' => ['required', 'array', 'max:50'], // row
            'matrix_a.*' => ['required', 'array', 'max:50'], // column
            'matrix_a.*.*' => ['required', 'integer', 'gt:0'], // value
            'matrix_b' => ['required', 'array', 'max:50', new ValidMatrices()],
            'matrix_b.*' => ['required', 'array','max:50'],
            'matrix_b.*.*' => ['required', 'integer', 'gt:0'],
        ];
    }

    /**
     * Get the custom messages for the rules
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'matrix_a.max' => "The Matrix A must not have more than :max rows.",
            'matrix_a.*.max' => "The Matrix A must not have more than :max columns.",

            'matrix_b.max' => "The Matrix B must not have more than :max rows.",
            'matrix_b.*.max' => "The Matrix B field must not have more than :max columns.",
        ];
    }


    /**
     * Helper function to validate incoming data
     *
     * @param array $data
     *
     * @return array
     */
    protected function validate(array $data): array
    {
        return Validator::make($data, $this->rules(), $this->messages())
            ->validate();
    }
}
