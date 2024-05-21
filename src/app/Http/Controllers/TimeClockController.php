<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\BreakRecord;
use App\Models\User;

class TimeClockController extends Controller
{
    public function index()
    {
        // デフォルトは出勤ボタンを有効に設定
        $isClockInDisabled = false;

        // ログインしているかどうかをチェック
        if (Auth::check()) {
            // 出勤ボタンの有効性を決定するロジック
            $user = Auth::user();
            $latestAttendance = $user->attendances()->latest()->first();
            $isClockInDisabled = $latestAttendance && is_null($latestAttendance->clockOut);
    }

        //休憩中かどうかセッションから取得
        $onBreak = session('onBreak', false);

        //  休憩時間のレコードを取得
        $breakRecords = BreakRecord::all();

        //$breakRecords が空でないかどうかを確認
        if ($breakRecords->isEmpty()) {
            // $breakRecordsが空の場合の処理
            $totalBreakTimeFormatted = 'No break records found';
        } else {
            //休憩時間の合計を秒単位で計算
            $totalBreakTimeSeconds = 0;
            foreach ($breakRecords as $breakRecord) {
                //休憩時間を秒単位に変換して合計に加算
            $totalBreakTimeSeconds += $breakRecord->break_time;
        }
        //合計した休憩時間を時間：分：秒の形式に変換
        $totalBreakTimeFormatted = gmdate("H:i:s", $totalBreakTimeSeconds);
    }
        // ビューにデータを渡して表示
        return view('index', compact('totalBreakTimeFormatted', 'onBreak', 'isClockInDisabled'));
    }

    public function clockIn(Request $request)
    {
        // 出勤が既にされているかチェック
        if (Auth::user()->isClockedIn()) {
            // すでに出勤している場合は何もせずにリダイレクトなど
            return redirect()->back()->with('error', 'すでに出勤しています。');
        }

        // 新しい出勤レコードを作成して保存
        $attendance = new Attendance();
        $attendance->users_id = $request->user()->id;
        $attendance->clockIn = now();
        $attendance->save();

        // 出勤が成功したことをフラッシュメッセージでセッションに保存
        $request->session()->flash('status', '出勤しました');

        //出勤状態をセッションに設定
        session(['isClockedIn' => true]);

        // 出勤ボタンを無効に設定
        $isClockInDisabled = true;

        // ビューを返す
        return redirect()->route('index')->with('isClockInDisabled', $isClockInDisabled);
    }

    public function clockOut(Request $request)
    {
        //直近の出勤レコードを取得
        $latestAttendance = Attendance::where('users_id', $request->user()->id)
                                ->orderBy('created_at', 'desc')
                                ->first();

        // 既に退勤済みであれば何もせずにリダイレクト
        if ($latestAttendance && !is_null($latestAttendance->clockOut)) {
            return redirect()->back()->withErrors(['already_clocked_out' => '既に退勤しています']);
        }

        // 0時を超えている場合の処理
        if (now()->diffInHours($latestAttendance->clockIn) >= 24) {
            $latestAttendance->clockOut = $latestAttendance->clockIn->copy()->addDay()->startOfDay(); // 0時で保存
        } else {
            $latestAttendance->clockOut = now();
        }

        // データベースの更新
        $latestAttendance->save();

        //退勤が成功したことをフラッシュメッセージでセッションに保存
        $request->session()->flash('status', '退勤しました');

        //休憩ボタンまたは休憩開始ボタンを押せるようにする
        session(['onBreak' => false]);

        // 出勤ボタンを有効に設定
        $isClockInDisabled = false;


        // ビューを返す
        return redirect()->route('index')->with('isClockInDisabled', $isClockInDisabled);
    }

}
