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
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'appointment_time_id' => 'required|exists:appointments,id',
            'price' => 'required|integer|min:0',
            'time_period' => 'nullable|date_format:Y-m-d H:i:s',
            'payment_date' => 'nullable|date_format:Y-m-d H:i:s',
            'service_date' => 'nullable|date_format:Y-m-d',
            'is_recurring' => 'nullable|boolean',
        ];
    }
}
