<?php

namespace Tests\Feature;

use App\Actions\MultiplyMatrices;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class MultiplyMatricesTest extends TestCase
{
    protected $validTestCases;
    protected $charactersAsResultTestCases;

    protected function setUp(): void
    {
        parent::setUp();

        // setup test data
        $this->validTestCases = collect([
            [
                'matrix_a' => [
                    [1, 2, 3],
                ],
                'matrix_b' => [
                    [4, 7],
                    [5, 8],
                    [6, 9],
                ],
                'expectedResult' => [
                    [32, 50]
                ]
            ],
            [
                'matrix_a' => [
                    [1, 2, 3],
                    [4, 5, 6],
                ],
                'matrix_b' => [
                    [7, 10],
                    [8, 11],
                    [9, 12],
                ],
                'expectedResult' => [
                    [50, 68],
                    [122, 167]
                ]
            ],
            [
                'matrix_a' => [
                    [1, 2, 3],
                    [4, 5, 6],
                    [7, 8, 9],
                ],
                'matrix_b' => [
                    [10, 13, 16,],
                    [11, 14, 17],
                    [12, 15, 18],
                ],
                'expectedResult' => [
                    [68, 86, 104],
                    [167, 212, 257],
                    [266, 338, 410],
                ]
            ],
        ]);

        // setup test data
        $this->charactersAsResultTestCases = collect([
            [
                'matrix_a' => [
                    [1, 2, 3],
                ],
                'matrix_b' => [
                    [4, 7],
                    [5, 8],
                    [6, 9],
                ],
                'expectedResult' => [
                    ['AF', 'AX']
                ]
            ],
            [
                'matrix_a' => [
                    [1, 2, 3],
                    [4, 5, 6],
                ],
                'matrix_b' => [
                    [7, 10],
                    [8, 11],
                    [9, 12],
                ],
                'expectedResult' => [
                    ['AX', 'BP'],
                    ['DR', 'FK']
                ]
            ],
            [
                'matrix_a' => [
                    [1, 2, 3],
                    [4, 5, 6],
                    [7, 8, 9],
                ],
                'matrix_b' => [
                    [10, 13, 16,],
                    [11, 14, 17],
                    [12, 15, 18],
                ],
                'expectedResult' => [
                    ['BP', 'CH', 'CZ'],
                    ['FK', 'HD', 'IW'],
                    ['JF', 'LZ', 'OT'],
                ]
            ],
        ]);
    }

    /** @test */
    public function it_can_multiple_two_matrices(): void
    {
        $multiplyMatrices = new MultiplyMatrices();

        $this->validTestCases->each(function ($testCase) use ($multiplyMatrices) {
            $result = $multiplyMatrices->handle($testCase['matrix_a'], $testCase['matrix_b']);
            $this->assertEquals($testCase['expectedResult'], $result);
        });
    }

    /** @test */
    public function it_can_multiple_two_matrices_and_return_result_in_characters(): void
    {
        $multiplyMatrices = new MultiplyMatrices();

        $this->charactersAsResultTestCases->each(function ($testCase) use ($multiplyMatrices) {
            $result = $multiplyMatrices->handle($testCase['matrix_a'], $testCase['matrix_b'], true);
            $this->assertEquals($testCase['expectedResult'], $result);
        });
    }

    /** @test */
    public function it_throw_validation_errors_on_incompatible_matrices(): void
    {
        $multiplyMatrices = new MultiplyMatrices();

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The column count in Matrix A (Current value: 3) must be equal to the row count of Matrix B (Current value: 2).');
        $multiplyMatrices->handle(
            [
                [1, 2, 3],
            ],
            [
                [4, 7],
                [5, 8],
            ]
        );
    }

    /** @test */
    public function it_throw_validation_errors_on_if_not_every_row_in_matric_a_have_same_columns_count(): void
    {
        $multiplyMatrices = new MultiplyMatrices();

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Every row in Matrix A must have the same column count.');
        $multiplyMatrices->handle(
            [
                [1, 1, 1],
                [1, 1],
            ],
            [
                [1, 1],
                [1, 1],
                [1, 1],
            ]
        );
    }

    /** @test */
    public function it_throw_validation_errors_on_if_not_every_row_in_matric_b_have_same_columns_count(): void
    {
        $multiplyMatrices = new MultiplyMatrices();

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Every row in Matrix B must have the same column count.');
        $multiplyMatrices->handle(
            [
                [1, 1, 1],
                [1, 1, 1],
            ],
            [
                [1, 1, 1],
                [1, 1],
                [1, 1],
            ]
        );
    }

    /** @test */
    public function it_throw_validation_errors_when_invalid_matrices_data_provided(): void
    {
        $multiplyMatrices = new MultiplyMatrices();

        $this->expectException(ValidationException::class);
        $multiplyMatrices->handle(
            [
                [1, 0, 1]
            ],
            [
                [1, 1],
                [0, 1],
                [1, 1],
            ]
        );
    }

    /** @test */
    public function it_throw_validation_errors_when_exceed_max_row(): void
    {
        $multiplyMatrices = new MultiplyMatrices();

        $matrixA = collect(range(1, 51))->map(fn ($i) => [1, 1])->all();

        $this->expectException(ValidationException::class);
        $multiplyMatrices->handle(
            $matrixA,
            [
                [1, 1],
                [1, 1],
            ]
        );
    }

    /** @test */
    public function it_throw_validation_errors_when_exceed_max_column(): void
    {
        $multiplyMatrices = new MultiplyMatrices();

        $matrixA = [
            collect(range(1, 51))->map(fn ($i) => 1)->all(),
        ];

        $matrixB = collect(range(1, 50))->map(fn ($i) => [1, 1])->all();

        $this->expectException(ValidationException::class);
        $multiplyMatrices->handle(
            $matrixA,
            $matrixB
        );
    }
}
