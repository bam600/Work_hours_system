<?php
//! laravelのルーティング機能を使うための宣言(Route::get（）Route::post（）が使える)
use Illuminate\Support\Facades\Route;
//! 会員登録処理を担当するRegisterControllerを読み込むshow()やstore()メソッドが使える
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\CreateController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

// PG01　商品一覧(トップ画面)-ログイン後*******************************************************************
    //!get:ログイン後の商品一覧を表示
    Route::get('/', [HomeController::class, 'index'])->name('item.index');
    
    Route::get('/mylist', [ExhibitionController::class, 'myLikedItems'])->name('exhibition.mylist');

// PG03 会員登録画面**************************************************************************************
    //! get:会員登録フォームを表示(showは表示という責務が明確)
    Route::get('/register', [RegisterController::class, 'show'])->name('register'); 

    //!フォーム送信後ユーザー情報を保存(storeは保存という責務が明確) register.blade
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
// pG04 ログイン画面**************************************************************************************
    //! ログインフォーム表示
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');

    //! ログイン処理(認証)
    Route::post('/login', [LoginController::class, 'store'])->name('login.store'); 
    
    //! ログアウト処理
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');    
// pG05 商品詳細画面***************************************************************************************
    //! GET /item/{id}：商品詳細ページを表示。{id} は商品ID。
    Route::get('/item/{id}', [ItemController::class, 'show'])->name('item.show');

    //! POST /item：商品登録や編集などの処理（出品機能など）
    Route::post('/item', [ItemController::class, 'store'])->name('item.store');

    //! いいねボタン
    Route::post('/item/{id}', [LikeController::class, 'store'])->name('like.store');
    Route::delete('/item/{id}', [LikeController::class, 'destroy'])->name('like.destroy');

// pG06 商品購入画面***************************************************************************************
    //! GET /purchase/{item_id}：商品詳細ページを表示。{id} は商品ID。
    Route::get('/purchase/{id}', [PurchaseController::class, 'confirm'])->name('item.confirm');
    //! POST /
    // 中間処理：セッションに保存して確認画面へ
    Route::post('/purchase', [PurchaseController::class, 'preparePurchase'])->name('item.prepare');
    // 支払い方法選択のリアルタイム更新
    Route::post('/purchase/{id}', [PurchaseController::class, 'selectPayment'])->name('payment.select');
    //注文完了
    Route::post('/order/{id}',[PurchaseController::class, 'order'])->name('order');

// pG07 住所変更画面***************************************************************************************
    //!GET
    // 住所編集画面（リンクで使う）
    Route::get('/purchase/address/{id}', [AddressController::class, 'editAddress'])->name('address');

    //!POST
    // 住所更新（フォーム送信先）
    Route::post('/purchase/address/{id}', [AddressController::class, 'change'])->name('address.change');

// PG09 プロフィール画面*******************************************************************************
    //! GET /mypage：プロフィールを表示。(editは表示の責務)
    Route::get('/mypage', [MypageController::class, 'index'])->middleware('auth')->name('mypage'); //会員登録画面で登録後のリダイレクト先

// PG10　プロフィール登録画面*******************************************************************************
    //! GET /mypage/profile：プロフィール編集画面を表示。(editは表示の責務)
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->middleware('auth')->name('profile.edit');  //会員登録画面で登録後のリダイレクト先

    //! POST /mypage/profile：編集内容を保存。
    Route::post('/mypage/profile', [ProfileController::class, 'update'])->middleware('auth')->name('profile.update'); // プロフィール画面の登録するボタンを起動を押下したら実装

//PG08 商品出品画面(ログイン認証されている場合可能)************************************************************************
    Route::middleware('auth')->group(function () {
    Route::get('/sell', [CreateController::class, 'create'])->name('item.create'); 
    Route::post('/sell', [CreateController::class, 'store'])->name('item.store'); 
    });


// 購入完了
    Route::post('/order/{id}', [PurchaseController::class, 'order'])->name('order');
    Route::get('/complete', [PurchaseController::class, 'complete'])->name('purchase.complete');