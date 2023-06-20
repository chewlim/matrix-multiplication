<?php

namespace App\Actions;

use App\Data\MatrixResultCell;
use App\Data\MatrixResultCellCollection;
use App\Data\MatrixResultRow;
use App\Data\MatrixResultRowCollection;
use App\Rules\ValidMatrices;
use Illuminate\Support\Facades\Validator;

class MultiplyMatrices
{
    /**
     * Multiply two matrices.
     *
     * @param array   $matrixA
     * @param array   $matrixB
     * @param bool $returnCharacters
     *
     * @return MatrixResultRowCollection
     */
    public function handle(array $matrixA, array $matrixB): MatrixResultRowCollection
    {
        $this->validate([
            'matrix_a' => $matrixA,
            'matrix_b' => $matrixB,
        ]);

        $columns = count($matrixB[0]); // number of columns in matrixB

        $rows = collect($matrixA)->map(function ($currentRow) use ($matrixB, $columns) {

            $cells = collect(range(0, $columns - 1))
                ->map(function ($columnIndex) use ($matrixB, $currentRow) {
                    $cellValue = $this->calculateCellValue($matrixB, $columnIndex, $currentRow);
                    return new MatrixResultCell($cellValue);
                });

            $matrixCells = new MatrixResultCellCollection($cells->all());

            return new MatrixResultRow($matrixCells);
        });

        return new MatrixResultRowCollection($rows->all());
    }

    /**
     * Helper function to calculate multiply of matrix for provided row and column.
     */
    protected function calculateCellValue(array $matrixB, int $columnIndex, array $row): int
    {
        // Transform columns in matrixB at current $index to row
        $transformed = collect($matrixB)
            ->map(fn ($item) => $item[$columnIndex])
            ->all();

        $cellValue = 0;
        foreach ($transformed as $pointerIndex => $item) {
            $cellValue += $row[$pointerIndex] * $item;
        }

        return $cellValue;
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
            'matrix_b.*' => ['required', 'array', 'max:50'],
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
