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
        $userTeamId = $userTeamSide === 'team' ? $match->team_id : $match->opponent_team_id;
        $opponentTeamId = $userTeamSide === 'team' ? $match->opponent_team_id : $match->team_id;

        return [
            // User team participants
            'participants' => 'required|array|min:1',
            'participants.*' => [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) use ($userTeamId) {
                    $user = \App\Models\User::find($value);
                    if (!$user) {
                        $fail('El jugador seleccionado no es válido.');
                        return;
                    }

                    // Check if user is a member of the team
                    $isMember = $user->teams()->where('team_id', $userTeamId)->exists();
                    if (!$isMember) {
                        $fail('El jugador ' . $user->name . ' no es miembro del equipo.');
                    }
                },
            ],

            // User team goals
            'goals' => 'nullable|array',
            'goals.*.scorer_id' => [
                'nullable',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    if ($value === null) {
                        return; // Allow unknown scorer
                    }
                    $participants = $this->input('participants', []);
                    if (!in_array($value, $participants)) {
                        $fail('El goleador debe estar en la lista de participantes.');
                    }
                },
            ],
            'goals.*.minute' => 'nullable|integer|min:1|max:120',

            // Opponent team participants
            'opponent_participants' => 'required|array|min:1',
            'opponent_participants.*' => [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) use ($opponentTeamId) {
                    $user = \App\Models\User::find($value);
                    if (!$user) {
                        $fail('El jugador seleccionado no es válido.');
                        return;
                    }

                    // Check if user is a member of the opponent team
                    $isMember = $user->teams()->where('team_id', $opponentTeamId)->exists();
                    if (!$isMember) {
                        $fail('El jugador ' . $user->name . ' no es miembro del equipo contrario.');
                    }
                },
            ],

            // Opponent team goals
            'opponent_goals' => 'nullable|array',
            'opponent_goals.*.scorer_id' => [
                'nullable',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    if ($value === null) {
                        return; // Allow unknown scorer
                    }
                    $opponentParticipants = $this->input('opponent_participants', []);
                    if (!in_array($value, $opponentParticipants)) {
                        $fail('El goleador debe estar en la lista de participantes del equipo contrario.');
                    }
                },
            ],
            'opponent_goals.*.minute' => 'nullable|integer|min:1|max:120',
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
            'goals.*.scorer_id.exists' => 'El goleador seleccionado no es válido.',
            'goals.*.minute.integer' => 'El minuto debe ser un número.',
            'goals.*.minute.min' => 'El minuto debe ser al menos 1.',
            'goals.*.minute.max' => 'El minuto no puede ser mayor a 120.',
            'opponent_participants.required' => 'Debes seleccionar al menos un participante del equipo contrario.',
            'opponent_participants.*.exists' => 'Uno de los participantes del equipo contrario no es válido.',
            'opponent_goals.*.scorer_id.exists' => 'El goleador del equipo contrario no es válido.',
            'opponent_goals.*.minute.integer' => 'El minuto debe ser un número.',
            'opponent_goals.*.minute.min' => 'El minuto debe ser al menos 1.',
            'opponent_goals.*.minute.max' => 'El minuto no puede ser mayor a 120.',
        ];
    }
}
