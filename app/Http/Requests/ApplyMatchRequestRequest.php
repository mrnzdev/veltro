<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Team;
use Illuminate\Foundation\Http\FormRequest;

class ApplyMatchRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $matchRequest = $this->route('matchRequest');

        return $this->user()->can('apply', $matchRequest);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'team_id' => [
                'required',
                'exists:teams,id',
                function ($attribute, $value, $fail) {
                    $team = Team::find($value);
                    if (!$team) {
                        $fail('El equipo seleccionado no es válido.');
                        return;
                    }

                    // Check if user is owner or captain
                    if (!$team->isOwnedBy($this->user()) && !$team->isCaptain($this->user())) {
                        $fail('Debes ser propietario o capitán del equipo para aplicar a un partido.');
                    }

                    // Check if team already applied
                    $matchRequest = $this->route('matchRequest');
                    $existingApplication = $matchRequest->applications()
                        ->where('applicant_team_id', $value)
                        ->where('status', 'pending')
                        ->exists();

                    if ($existingApplication) {
                        $fail('Este equipo ya ha aplicado a esta solicitud de partido.');
                    }
                },
            ],
            'message' => 'nullable|string|max:500',
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
            'team_id.required' => 'Debes seleccionar un equipo.',
            'team_id.exists' => 'El equipo seleccionado no existe.',
            'message.max' => 'El mensaje no puede tener más de 500 caracteres.',
        ];
    }
}
