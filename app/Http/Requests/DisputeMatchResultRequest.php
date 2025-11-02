<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DisputeMatchResultRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $match = $this->route('match');
        
        // User must have permission to record results
        if (!$match->canRecordResults($this->user())) {
            return false;
        }

        // Results must not already be confirmed
        if ($match->areResultsConfirmed()) {
            return false;
        }

        // Opponent must have submitted results
        $userTeamSide = $match->getUserTeamSide($this->user());
        $hasOpponentSubmitted = ($userTeamSide === 'team' && $match->hasOpponentSubmittedResults()) ||
                                ($userTeamSide === 'opponent' && $match->hasTeamSubmittedResults());

        return $hasOpponentSubmitted;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'reason' => 'required|string|min:10|max:500',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'reason.required' => 'Debes proporcionar una razón para disputar los resultados.',
            'reason.min' => 'La razón debe tener al menos 10 caracteres.',
            'reason.max' => 'La razón no puede exceder 500 caracteres.',
        ];
    }
}
