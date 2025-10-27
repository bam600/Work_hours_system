<div>
  {{-- 見た目はセレクトでもラジオでもOK。ここではセレクト例 --}}
  <label class="block mb-2">支払い方法を選択</label>
  <select wire:model="paymentId" class="form-control">
    <option value="">-- 選択してください --</option>
    @foreach ($payments as $p)
      <option value="{{ $p->id }}">{{ $p->name }}</option>
    @endforeach
  </select>

  {{-- ★ 送信用：フォームと一緒にPOSTされる hidden --}}
  <input type="hidden" name="payment_id" value="{{ $paymentId }}">
</div>