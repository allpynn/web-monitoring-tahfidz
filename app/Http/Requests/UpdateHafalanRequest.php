<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHafalanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $hafalan = $this->route('hafalan');

        return $this->user()->can('update', $hafalan);
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
            'ayat_dari' => 'required_if:is_present,1|nullable|integer|min:1',
            'ayat_sampai' => 'required_if:is_present,1|nullable|integer|min:1',
            'status' => 'required_if:is_present,1|nullable|in:Lancar,Perlu Perbaikan',
            'tanggal' => 'required|date',
            'notes' => 'nullable|string',
            'parent_comment' => 'nullable|string',
        ];
    }
}
