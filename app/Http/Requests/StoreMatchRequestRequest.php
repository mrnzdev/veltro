<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Team;
use Illuminate\Foundation\Http\FormRequest;

class StoreMatchRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Check if user can create match requests
        return $this->user()->can('create', \App\Models\MatchRequest::class);
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
                        $fail('Debes ser propietario o capitán del equipo para crear una solicitud de partido.');
                    }
                },
            ],
            'match_datetime' => [
                'required',
                'date',
                'after:now',
            ],
            'location' => 'required|string|max:255',
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
            'match_datetime.required' => 'La fecha y hora del partido son obligatorias.',
            'match_datetime.date' => 'La fecha y hora del partido no son válidas.',
            'match_datetime.after' => 'La fecha y hora del partido deben ser en el futuro.',
            'location.required' => 'La ubicación es obligatoria.',
            'location.max' => 'La ubicación no puede tener más de 255 caracteres.',
        ];
    }
}
