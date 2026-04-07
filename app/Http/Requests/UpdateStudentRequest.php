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
            'nis' => 'required|string|unique:students,nis,'.$studentId,
            'parent_id' => 'required|exists:users,id',
            'target_juz' => 'required|integer|min:1|max:30',
            'target_date' => 'nullable|date',
        ];
    }
}
