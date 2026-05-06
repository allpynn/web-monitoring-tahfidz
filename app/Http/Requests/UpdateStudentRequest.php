<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $student = $this->route('student');

        return $this->user()->can('update', $student);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $studentId = $this->route('student')->id;

        return [
            'name' => 'required|string|max:255',
            'nis' => 'required|string|digits:10|unique:students,nis,'.$studentId,
            'parent_ids' => 'required|array',
            'parent_ids.*' => 'exists:users,id',
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
