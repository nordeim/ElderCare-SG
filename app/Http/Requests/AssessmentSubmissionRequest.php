<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssessmentSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'answers' => ['required', 'array'],
            'answers.care_context' => ['nullable', 'string', 'in:mom,dad,relative,other'],
            'answers.support_frequency' => ['nullable', 'string', 'in:weekdays,selected_days,occasional,unknown'],
            'answers.mobility' => ['nullable', 'string', 'in:independent,assistance,full_support'],
            'answers.cognitive_support' => ['nullable', 'string', 'in:engaged,mild_changes,dementia'],
            'answers.health_considerations' => ['nullable', 'array'],
            'answers.health_considerations.*' => ['string'],
            'answers.transportation' => ['nullable', 'string', 'in:yes,sometimes,no'],
            'answers.caregiver_goals' => ['nullable', 'array'],
            'answers.caregiver_goals.*' => ['string'],
            'answers.contact_preference' => ['nullable', 'string', 'in:book_now,email_me,browsing'],
            'segment_key' => ['nullable', 'string'],
            'meta' => ['nullable', 'array'],
        ];
    }

    public function attributes(): array
    {
        return [
            'answers.health_considerations.*' => 'health consideration',
            'answers.caregiver_goals.*' => 'caregiver goal',
        ];
    }
}
