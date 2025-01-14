<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreschedulerecordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'service_date' => 'required|date',
//            'service_id' => 'required|exists:services,id',
            'master_id' => 'required|exists:masters,id',
//            'available_times' => 'required|exists:appointments,id',  // 修正名稱對應
        ];
    }
}
