<?php

namespace App\Http\Requests\Pengguna;

use App\Models\Pengguna;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePenggunaRequest extends FormRequest
{
    public function authorize(): bool
    {
        $target = $this->route('pengguna');

        if (! $target instanceof Pengguna) {
            return false;
        }

        return $this->user()?->can('update', $target) ?? false;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $target = $this->route('pengguna');
        $penggunaId = $target instanceof Pengguna ? $target->id_pengguna : $target;

        return array_merge(StorePenggunaRequest::commonRules(), [
            'username' => [
                'required',
                'string',
                'max:50',
                'regex:/^[A-Za-z0-9_.-]+$/',
                Rule::unique('pengguna', 'username')->ignore($penggunaId, 'id_pengguna'),
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return StorePenggunaRequest::commonMessages();
    }
}
