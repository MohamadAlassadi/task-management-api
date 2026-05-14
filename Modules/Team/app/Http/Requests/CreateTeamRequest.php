<?php

namespace Modules\Team\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTeamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'owner_id' => 'required|exists:users,user_id',
            'users' => 'nullable|array',
            'users.*' => 'exists:users,user_id',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'owner_id' => auth()->user()->user_id,
        ]);
    }
}