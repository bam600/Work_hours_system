<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Staff;
use Illuminate\Validation\Rule;

class AttendanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    // ステータスのバリデーション
    public function rules()
    {
        return [
            'status' => 'required|in:checkin,break,endbreak,checkout',
        ];
    }

    // バリデーションエラーメッセージの設定
    public function messages()
    {
        return [
            'status.required' => 'ステータスは必須です。',
            'status.in' => 'ステータスの値が不正です。',
        ];

    }
}
