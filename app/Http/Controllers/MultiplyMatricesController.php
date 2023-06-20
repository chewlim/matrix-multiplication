<?php

namespace App\Http\Controllers;

use App\Actions\MultiplyMatrices;
use App\Http\Requests\MultiplyMatricesRequest;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;

class MultiplyMatricesController extends Controller
{
    public function show(): Response
    {
        return Inertia::render('Index');
    }

    public function store(MultiplyMatricesRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $result = (new MultiplyMatrices(true))
            ->handle($validated['matrix_a'], $validated['matrix_b']);

        return response()->json($result->toArray());
    }
}
