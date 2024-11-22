<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateappointmenttimeRequest extends FormRequest
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
            'service_date' => 'required|date|after_or_equal:today', // 日期必須有效，且不能是今天之前的日期
            'start_time' => 'required|date_format:H:i', // 開始時間必須符合 "H:i" 格式
            'end_time' => 'required|date_format:H:i|after:start_time', // 結束時間必須符合 "H:i" 格式，且要晚於開始時間
        ];
    }
}
