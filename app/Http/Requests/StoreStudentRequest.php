<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'nis' => 'required|string|digits:10|unique:students,nis',
            'parent_names' => 'required|array|min:1',
            'parent_names.*' => 'required|string|max:255',
            'parent_phones' => 'required|array|min:1',
            'parent_phones.*' => 'required|string|max:20',
            'target_juz' => 'required|integer|min:1|max:30',
            'target_date' => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'nis.unique' => 'Gagal: Data ini sudah ada! NISN tersebut sudah terdaftar di dalam sistem.',
            'nis.digits' => 'Gagal: NISN harus berjumlah persis 10 angka.',
        ];
    }
}
