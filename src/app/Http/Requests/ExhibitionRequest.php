<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
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
            //カテゴリー
            'categories' => ['array'], // 配列であることを確認
            'categories.*' => ['integer', 'exists:categories,id'], // 各要素が有効なIDであること
            //商品condition
            'condition_id' => ['required', 'integer', 'exists:conditions,id'],
            //商品名
            'product_name' => ['required', 'string', 'max:255'],
            //ブランド
            'brand' => ['nullable', 'string', 'max:255'],
            //商品の説明
            'description'=> ['required', 'string', 'max:1000'],
            //価格
            'price'=> ['required', 'integer','min:1'],
            ];

    }

    // バリデーションエラーメッセージの設定
    public function messages()
     {
         return [
            'categories.*.integer' => 'カテゴリーの選択が不正です',
            'categories.*.exists'=>'選択されたカテゴリーが存在しません',
            'condition_id.required' => '商品の状態は必須です。',
            'condition_id.exists' => '選択された状態が存在しません',
            'product_name.required' => '商品名は必須です',
            'brand.string' => 'ブランド名は文字列で入力してください。',
            'brand.max' => 'ブランド名は255文字以内で入力してください',
            'description.required' => '商品の説明は必須です。',
            'description.max' => '商品の説明は1000文字以内で入力してください。',
            'price.required' => '価格は必須です',
            'price.integer' => '価格は半角数字で入力してください。',
            'price.min' => '価格は1円以上で入力してください'
         ];
    }

}
