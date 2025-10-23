<?php
/** PG09 住所変更画面 controller */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AddressRequest; // 既に作成済み想定（無ければ下に代替案あり）
use App\Models\Profile;


class AddressController extends Controller
{
    public function __construct()
    {
        // 住所編集/更新はログイン必須
        $this->middleware('auth')->only(['editAddress', 'change']);
    }

    /** 住所変更画面の表示 (GET /purchase/address/{id}) */
    public function editAddress($id)
    {
        $profile = auth()->user()->profile ?? null;

        return view('mypage.address', [
            'exhibition_id' => $id, // ← これを Blade で form の route引数に使う
            'postal_code'   => session('purchase_postal_code', $profile?->postal_code ?? ''),
            'address'       => session('purchase_address',     $profile?->address ?? ''),
            'building'      => session('purchase_building',    $profile?->building ?? ''),
        ]);
    }

    /** 住所変更の保存処理 (POST /purchase/address/{id}) */
    public function change(AddressRequest $request, $id)
    {
        $user = auth()->user();

        // プロフィールを安全に用意（無ければ作る）
        $profile = $user->profile ?? new Profile(['user_id' => $user->id]);
        $profile->postal_code = $request->postal_code;
        $profile->address     = $request->address;
        $profile->building    = $request->building ?? '';
        $profile->user_id     = $user->id; // 念のため
        $profile->save();

        // 購入確認画面が読むセッション値も更新
        session([
            'purchase_postal_code' => $request->postal_code,
            'purchase_address'     => $request->address,
            'purchase_building'    => $request->building ?? '',
        ]);

        // ★ ここが肝：{id} を付けて確認画面へ戻す
        return redirect()->route('item.confirm', ['id' => $id])
            ->with('success', '住所を更新しました');
    }
    
}