<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    
public function rules()
{
    return [
        'exhibition_id' => ['required', 'integer', 'exists:exhibitions,id'],
        'payment_id' => ['required', 'integer'],
        'postal_code' => ['required', 'string'],
        'address_line' => ['required', 'string'],
        'building_name' => ['nullable', 'string'],
        'price' => ['required', 'integer'], // ← これが必要
    ];
}

    public function messages()
    {
        return [
            'payment_id.required'  => '支払い方法を選択してください',
            'postal_code.required' => '郵便番号を入力してください',
            'address_line.required' => '住所を入力してください',
        ];
    }
}