<?php

namespace App\Http\Requests;

use App\Support\AssessmentQuestions;
use Illuminate\Foundation\Http\FormRequest;

class StoreAssessmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $keys = array_keys(AssessmentQuestions::getQuestions());
        
        $rules = [
            'answers' => ['required', 'array', 'size:12'],
        ];

        foreach ($keys as $key) {
            $rules["answers.{$key}"] = ['required', 'integer', 'between:1,5'];
        }

        return $rules;
    }
}
