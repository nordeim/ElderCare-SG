<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssessmentSubmissionRequest;
use App\Services\AssessmentService;
use Illuminate\Http\JsonResponse;

class AssessmentController extends Controller
{
    public function __construct(
        protected AssessmentService $assessmentService,
    ) {
    }

    public function __invoke(AssessmentSubmissionRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $answers = $validated['answers'];

        $summary = $this->assessmentService->createSummary($answers);

        $payload = [
            'segment_key' => $summary['segment_key'] ?? null,
            'answers' => $answers,
            'meta' => $validated['meta'] ?? null,
        ];

        $this->assessmentService->logOutcome($payload);

        return response()->json([
            'segment' => $summary['segment'] ?? [],
            'segment_key' => $summary['segment_key'] ?? null,
        ]);
    }
}
