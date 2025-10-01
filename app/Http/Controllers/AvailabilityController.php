<?php

namespace App\Http\Controllers;

use App\Services\AvailabilityService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AvailabilityController extends Controller
{
    public function __construct(protected AvailabilityService $availability)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $force = (bool) $request->boolean('refresh');

        $payload = $this->availability->getAvailability($force);

        return response()->json($payload);
    }
}
