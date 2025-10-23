<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exhibition;
use App\Models\Payment;
use App\Models\Purchase;
use App\Http\Requests\PurchaseRequest;

class PurchaseController extends Controller
{
    public function preparePurchase(Request $request)
    {
        $id = $request->input('id');
        $exhibition = Exhibition::findOrFail($id);

        session()->put('selected_exhibition_id', $exhibition->id);
        session()->put('selected_exhibition_name', $exhibition->product_name);
        session()->put('selected_price', $exhibition->price);
        session()->put('selected_img', $exhibition->img);

        return redirect()->route('item.confirm',['id' => $exhibition->id]);
    }

    public function confirm(Request $request, $id)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', '購入にはログインが必要です');
        }


        //セッションが空でもページが開けるよう、まず id から商品取得
        $exhibition = Exhibition::with('condition')->findOrFail($id);

        // セッションに値があれば優先、なければモデルから
        $exhibition_id = session('selected_exhibition_id', $exhibition->id);
        $price         = session('selected_price', $exhibition->price);
        $img           = session('selected_img', $exhibition->img);

        $payments = Payment::all();
        $profile  = auth()->user()->profile;

        $postal_code = session('purchase_postal_code', $profile->postal_code ?? '');
        $address     = session('purchase_address',     $profile->address ?? '');
        $building    = session('purchase_building',    $profile->building ?? '');

        // 支払い方法の選択
        $payment_id = $request->input('payment_id');
        $selected_payment_id = $payment_id ?? session('selected_payment_id');
        $payment_name = Payment::find($selected_payment_id)?->name ?? '未選択';

        // セッションに保存（任意）
        if ($payment_id) {
        session()->put('selected_payment_id', $payment_id);
        }

        return view('item.purchase', [
            'exhibition_id' => $exhibition_id,
            'payments' => Payment::all(),
            'exhibition'    => $exhibition,
            'price'         => $price,
            'img'           => $img,
            'postal_code'   => $postal_code,
            'address'       => $address,
            'building'      => $building,
            'selected_payment_id' => $selected_payment_id, // ← 追加
            'payment_name'        => $payment_name,        // ← 追加
            'message'       => '購入内容をご確認ください',
        ]);
    }


    public function store(Request $request, $id)
    {
        // 購入処理（例：DB保存など）

        return redirect()->route('item.confirm',['id' => $id])
            ->with('success', '購入処理が完了しました');
    }

public function selectPayment(Request $request, $id)
{
    // 商品情報を取得（存在しない場合は404）
    $exhibition = Exhibition::findOrFail($id);

    // 支払い方法の選択処理
    $paymentId = $request->input('payment_id');
    $selectedPayment = Payment::find($paymentId);

    // セッションに保存（後続の購入処理でも使える）
    session([
        'selected_payment_id' => $paymentId,
        'selected_payment_name' => $selectedPayment ? $selectedPayment->name : '未選択',
    ]);

    // 確認画面を再表示
    return view('item.purchase', [
        'exhibition' => $exhibition,
        'payments' => Payment::all(),
        'selected_payment_id' => $paymentId,
        'payment_name' => $selectedPayment ? $selectedPayment->name : '未選択',
    ]);
}

    // 購入完了
    public function order(PurchaseRequest $request, $id)
{
    $validated = $request->validated();

    $exhibition = Exhibition::findOrFail($validated['exhibition_id']);

    Purchase::create([
        'user_id' => auth()->id(),
        'exhibition_id' => $exhibition->id,
        'payment_id' => $validated['payment_id'],
        'postal_code' => $validated['postal_code'],
        'address_line' => $validated['address_line'],
        'building_name' => $validated['building_name'] ?? null,
        'price' => $exhibition->price,
        'purchased_at' => now(),
    ]);

    Exhibition::where('id', $exhibition->id)->update(['status' => 'sold']);

    return redirect()->route('purchase.complete');
}

    public function complete()
{
    return view('mypage.complete');
}

}
