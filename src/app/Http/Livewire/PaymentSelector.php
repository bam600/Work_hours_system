<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Payment;

class PaymentSelector extends Component
{
    public $exhibitionId;
    public $paymentId;   // 選択中のID
    public $payments = [];

    // 初期値を受け取れるように
    public $default = null;

    public function mount($exhibitionId, $default = null)
    {
        $this->exhibitionId = $exhibitionId;
        $this->payments = Payment::all();

        // 1) 直近の選択（セッション） 2) 画面からのdefault 3) 未選択 の順で初期化
        $this->paymentId = session('selected_payment_id', $default);
    }

    // 変更されたらセッションに保存して保持
    public function updatedPaymentId($value)
    {
        session(['selected_payment_id' => $value]);
    }

    public function render()
    {
        return view('livewire.payment-selector');
    }
}
