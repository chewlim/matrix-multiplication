<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class MultiplyMatricesControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_can_view_multiply_form()
    {
        $this->get('multiply')
            ->assertInertia(fn (Assert $assert) => $assert->component('Index'));
    }

    public function test_can_multiply_matrices()
    {
        $this->post('multiply', [
            'matrix_a' => [
                [1, 2, 3],
            ],
            'matrix_b' => [
                [4, 7],
                [5, 8],
                [6, 9],
            ],
        ])
            ->assertSuccessful()
            ->assertExactJson([
                ['AF', 'AX']
            ]);
    }

    public function test_throw_validation_exception_for_incompatible_matrices()
    {
        $this->json('POST', 'multiply', [
            'matrix_a' => [
                [1, 2, 3],
            ],
            'matrix_b' => [
                [4, 7],
                [5, 8],
            ],
        ])->assertStatus(422);
    }
}
