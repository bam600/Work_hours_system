<?php
// このクラスが属する名前空間。laravelでは通常App\Http\Requestsに配置
namespace App\Http\Requests;
// laravelのFormRequestクラスを継承するために必要な宣言
use Illuminate\Foundation\Http\FormRequest;

// PG07　送付先住所変更画面*******************************************************************************

class AddressRequest extends FormRequest
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
    public function rules()
    {
        return [
            'postal_code' => ['required','regex:/^\d{3}-\d{4}$/'],
            'address'     => ['required'],
        ];

    }

     // バリデーションエラーメッセージの設定
    public function messages()
    { 
        return [
            'postal_code.required' => '郵便番号を入力してください',
            'postal_code.regex' => '郵便番号はハイフンを含めた8桁で入力してください',
            'address.required' => '住所を入力してください',
        ];

    }
}
