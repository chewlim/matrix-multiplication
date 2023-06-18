<?php

namespace App\Actions;

use App\Rules\ValidMatrices;
use Illuminate\Support\Facades\Validator;

class MultiplyMatrices
{
    public function handle(array $matrixA, array $matrixB, bool $returnCharacters = false): array
    {
        $this->validate([
            'matrix_a' => $matrixA,
            'matrix_b' => $matrixB,
        ]);

        $result = [];

        $columns = count($matrixB[0]); // number of columns in matrixB

        foreach ($matrixA as $row => $currentRow) {
            for ($k = 0; $k < $columns; $k++) {
                $transformed = $this->transformColumns($k, $matrixB);

                $result[$row][$k] = 0; // initialise

                foreach ($transformed as $index => $item) {
                    $result[$row][$k] += $currentRow[$index] * $item;
                }
            }
        }

        return $returnCharacters ? $this->transformToCharacters($result) : $result;
    }

    /**
     * @param array $data Two dimenstional array
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

    protected function getCharacters(int $value)
    {
        // chr(65) = A
        $balance = ($value - 1) % 26; // e.g (32 - 1) % 26  = 5

        $suffixLetter = chr(65 + $balance); // e.g 65 + 5 = F

        $cycle = intval(($value - 1) / 26); // e.g. (32 - 1) / 26 = 1.19

        if ($cycle > 0) {
            return $this->getCharacters($cycle) . $suffixLetter;
        }

        return $suffixLetter;
    }

    /**
     * Transform columns in matrixB at current $index to single dimension array
     */
    protected function transformColumns(int $index, array $matrixB): array
    {
        return collect($matrixB)->map(fn ($item) => $item[$index])->all();
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


    public function validate(array $data): array
    {
        return Validator::make($data, $this->rules(), $this->messages())
            ->validate();
    }
}
