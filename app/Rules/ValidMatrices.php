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
   */
  public function validate(string $attribute, mixed $value, Closure $fail): void
  {
    // Get first row of the Matrix A then count the column
    $columnCountOfMatrixA = count(collect($this->data['matrixA'])->first());
    
    // MatrixA: Make sure every row has the same column 
    $hasSameColumnCount = collect($this->data['matrixA'])->every(function ($value, int $key) use ($columnCountOfMatrixA) {
        return count($value) === $columnCountOfMatrixA;
    });

    if(! $hasSameColumnCount) {
      $fail("Every row in matrix A must have the same column count.");
    }

    $rowCountOfMatrixB = count($this->data['matrixB']);
    
    // The column count in the first matrix should be equal to the row count of the second matrix
    if($columnCountOfMatrixA !== $rowCountOfMatrixB) {
      $fail("The column count ({$columnCountOfMatrixA}) in the first matrix must be equal to the row count ({$rowCountOfMatrixB}) of the second matrix.");
    }
  }
}
