<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHafalanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->role === 'guru' || $this->user()->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'student_id' => 'required|exists:students,id',
            'is_present' => 'required|boolean',
            'juz' => 'required_if:is_present,1|nullable|integer|min:1|max:30',
            'surah' => 'required_if:is_present,1|nullable|string',
            'ayat' => 'required_if:is_present,1|nullable|string',
            'status' => 'required_if:is_present,1|nullable|in:Lancar,Perlu Perbaikan',
            'notes' => 'nullable|string',
        ];
    }
}
