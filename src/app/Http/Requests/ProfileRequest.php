<?php
// このクラスが属する名前空間。laravelでは通常App\Http\Requestsに配置
namespace App\Http\Requests;
// laravelのFormRequestクラスを継承するために必要な宣言
use Illuminate\Foundation\Http\FormRequest;

// PG10　プロフィール編集画面*******************************************************************************

// ProfileRequestクラスの定義FormRequestを継承してバリデーションルールやメッセージを定義する
class ProfileRequest extends FormRequest
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
        $userId = $this->user()->id ?? null;

        return [
            'user_name' => ['required', 'max:20', 'unique:users,user_name,' . $userId],
            'postal_code' => ['required','regex:/^\d{3}-\d{4}$/'],
            'address'     => ['required'],
            'building'    => ['nullable'],
        ];

    }

     // バリデーションエラーメッセージの設定
    public function messages()
    {
    return [
            // 'image.required' => 'プルフィール画像をアップロードしてください',
            // 'image.image' => '画像ファイルを選択してください',
            // 'image.mimes' => '画像はjpegまたはpngファイルを選択してください',
            'user_name.required' => 'お名前を入力してください',
            'postal_code.required' => '郵便番号を入力してください',
            'postal_code.regex' => '郵便番号はハイフンを含めた8桁で入力してください',
            'address.required' => '住所を入力してください',
    ];

    }
}
