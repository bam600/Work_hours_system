<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use App\Models\Staff;

class AttendanceInfoRequest extends FormRequest
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
            'clockin' => 'required|date_format:H:i',
            'clockout' => 'required|date_format:H:i|after:clockin',
            'break_start.*' => 'nullable|date_format:H:i',
            'break_end.*' => 'nullable|date_format:H:i',
            'note' => 'required',
            
        ];
    }

    // バリデーションエラーメッセージの設定
    public function messages()
    {
        return [
            'break_start.*.date_format' => '休憩時間が不適切な値です',
            'break_end.*.date_format' => '休憩時間もしくは退勤時間が不適切な値です',
            'break_start.*.required' => '休憩時間が不適切な値です',
            'break_end.*.required' => '休憩時間もしくは退勤時間が不適切な値です',
            'clockin.required' => '出勤時間もしくは退勤時間が不適切な値です',
            'clockin.date_format' => '出勤時間もしくは退勤時間が不適切な値です',
            'clockout.required' => '出勤時間もしくは退勤時間が不適切な値です',
            'clockout.date_format' => '出勤時間もしくは退勤時間が不適切な値です',
            'clockout.after' => '出勤時間もしくは退勤時間が不適切な値です',
            'note.required' => '備考を記入してください。',
        ];
    }

    public function withValidator($validator)
{
    $validator->after(function ($validator) {
        $breakStarts = $this->input('break_start', []);
        $breakEnds = $this->input('break_end', []);
        $clockin = $this->input('clockin');
        $clockout = $this->input('clockout');

        $clockinTime = strtotime($clockin);
        $clockoutTime = strtotime($clockout);

        // 出勤・退勤の形式が不正ならスキップ（rules() で処理される）
        if ($clockinTime === false || $clockoutTime === false) {
            return;
        }

        foreach ($breakStarts as $index => $start) {
            $end = $breakEnds[$index] ?? null;

            // 両方空ならスキップ
            if (empty($start) && empty($end)) {
                continue;
            }

            $startTime = strtotime($start);
            $endTime = strtotime($end);

            // どちらかが未入力、または形式不正
            if (!$start || !$end || $startTime === false || $endTime === false) {
                $validator->errors()->add("break_start.$index", "休憩時間もしくは退勤時間が不適切な値です");
                continue;
            }

            // 開始が終了より後
            if ($startTime >= $endTime) {
                $validator->errors()->add("break_start.$index", "休憩時間が不適切な値です");
            }

            // 出勤前 or 退勤後
            if ($startTime < $clockinTime || $startTime > $clockoutTime) {
                $validator->errors()->add("break_start.$index", "休憩時間が不適切な値です");
            }

            //退勤後
            if ($endTime > $clockoutTime) {
                $validator->errors()->add("break_start.$index", "休憩時間が不適切な値です");
            }
        }
    });
}
}