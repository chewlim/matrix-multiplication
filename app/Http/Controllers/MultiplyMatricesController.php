<?php

namespace App\Http\Controllers;

use App\Actions\MultiplyMatrices;
use App\Http\Requests\MultiplyMatricesRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MultiplyMatricesController extends Controller
{
    public function show(Request $request): Response
    {
        return Inertia::render('Index', [
            
        ]);
    }

    public function store(MultiplyMatricesRequest $request)
    {
        $validated = $request->validated();

        $result = (new MultiplyMatrices())
            ->handle($validated['matrix_a'], $validated['matrix_b'], true);

        return $result;
    }
}
