<?php

namespace Tests\Feature;

use App\Actions\MultiplyMatrices;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class MultiplyMatricesTest extends TestCase
{
    protected $validTestCases;
    protected $charactersAsResultTestCases;
    protected $invalidTestCases;

    protected function setUp(): void
    {
        parent::setUp();

        // setup test data
        $this->validTestCases = collect([
            [
                'matrixA' => [
                    [1, 2, 3],
                ],
                'matrixB' => [
                    [4, 7],
                    [5, 8],
                    [6, 9],
                ],
                'expectedResult' => [
                    [32, 50]
                ]
            ],
            [
                'matrixA' => [
                    [1, 2, 3],
                    [4, 5, 6],
                ],
                'matrixB' => [
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
                'matrixA' => [
                    [1, 2, 3],
                    [4, 5, 6],
                    [7, 8, 9],
                ],
                'matrixB' => [
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
                'matrixA' => [
                    [1, 2, 3],
                ],
                'matrixB' => [
                    [4, 7],
                    [5, 8],
                    [6, 9],
                ],
                'expectedResult' => [
                    ['AF', 'AX']
                ]
            ],
            [
                'matrixA' => [
                    [1, 2, 3],
                    [4, 5, 6],
                ],
                'matrixB' => [
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
                'matrixA' => [
                    [1, 2, 3],
                    [4, 5, 6],
                    [7, 8, 9],
                ],
                'matrixB' => [
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

        $this->invalidTestCases = collect([
            [
                'matrixA' => [
                    [1, 2, 3],
                ],
                'matrixB' => [
                    [4, 7],
                    [5, 8],
                ],
            ],
            [
                'matrixA' => [
                    [1, 1, 1],
                    [1, 1],
                ],
                'matrixB' => [
                    [1, 1],
                    [1, 1],
                    [1, 1],
                ],
            ],
            [
                'matrixA' => [
                    [1, 0, 1],
                ],
                'matrixB' => [
                    [1, 1],
                    [0, 1],
                    [1, 1],
                ],
            ],
        ]);
    }

    /** @test */
    public function it_can_multiple_two_matrices(): void
    {
        $multiplyMatrices = new MultiplyMatrices();

        $this->validTestCases->each(function ($testCase) use ($multiplyMatrices) {
            $result = $multiplyMatrices->handle($testCase['matrixA'], $testCase['matrixB']);
            $this->assertEquals($testCase['expectedResult'], $result);
        });
    }

    /** @test */
    public function it_can_multiple_two_matrices_and_return_result_in_characters(): void
    {
        $multiplyMatrices = new MultiplyMatrices();

        $this->charactersAsResultTestCases->each(function ($testCase) use ($multiplyMatrices) {
            $result = $multiplyMatrices->handle($testCase['matrixA'], $testCase['matrixB'], true);
            $this->assertEquals($testCase['expectedResult'], $result);
        });
    }

    /** @test */
    public function it_throw_validation_errors_on_incompatible_matrices(): void
    {
        $multiplyMatrices = new MultiplyMatrices();

        $this->expectException(ValidationException::class);
        $multiplyMatrices->handle(
            $this->invalidTestCases->first()['matrixA'],
            $this->invalidTestCases->first()['matrixB']
        );
    }

    /** @test */
    public function it_throw_validation_errors_on_if_not_every_row_in_matric_have_same_columns_count(): void
    {
        $multiplyMatrices = new MultiplyMatrices();

        $this->expectException(ValidationException::class);
        $multiplyMatrices->handle(
            $this->invalidTestCases->skip(1)->first()['matrixA'],
            $this->invalidTestCases->skip(1)->first()['matrixB']
        );
    }

    /** @test */
    public function it_throw_validation_errors_when_invalida_matrices_data_provided(): void
    {
        $multiplyMatrices = new MultiplyMatrices();

        $this->expectException(ValidationException::class);
        $multiplyMatrices->handle(
            $this->invalidTestCases->skip(2)->first()['matrixA'],
            $this->invalidTestCases->skip(2)->first()['matrixB']
        );
    }
}
