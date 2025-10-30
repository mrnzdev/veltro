<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMatchResultRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $match = $this->route('match');
        return $match->canRecordResults($this->user());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $match = $this->route('match');
        $userTeamSide = $match->getUserTeamSide($this->user());
        $teamId = $userTeamSide === 'team' ? $match->team_id : $match->opponent_team_id;

        return [
            'participants' => 'required|array|min:1',
            'participants.*' => [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) use ($teamId) {
                    $user = \App\Models\User::find($value);
                    if (!$user) {
                        $fail('El jugador seleccionado no es válido.');
                        return;
                    }

                    // Check if user is a member of the team
                    $isMember = $user->teams()->where('team_id', $teamId)->exists();
                    if (!$isMember) {
                        $fail('El jugador ' . $user->name . ' no es miembro del equipo.');
                    }
                },
            ],
            'goals' => 'nullable|array',
            'goals.*.scorer_id' => [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    $participants = $this->input('participants', []);
                    if (!in_array($value, $participants)) {
                        $fail('El goleador debe estar en la lista de participantes.');
                    }
                },
            ],
            'goals.*.minute' => 'required|integer|min:1|max:120',
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
            'participants.required' => 'Debes seleccionar al menos un participante.',
            'participants.*.exists' => 'Uno de los participantes seleccionados no es válido.',
            'goals.*.scorer_id.required' => 'Debes seleccionar quién anotó el gol.',
            'goals.*.scorer_id.exists' => 'El goleador seleccionado no es válido.',
            'goals.*.minute.required' => 'Debes indicar el minuto del gol.',
            'goals.*.minute.integer' => 'El minuto debe ser un número.',
            'goals.*.minute.min' => 'El minuto debe ser al menos 1.',
            'goals.*.minute.max' => 'El minuto no puede ser mayor a 120.',
        ];
    }
}
