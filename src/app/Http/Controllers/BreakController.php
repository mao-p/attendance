<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\BreakRecord;


class BreakController extends Controller
{
    public function index()
    {

        //休憩中かどうかをセッションから取得
        $onBreak = session('onBreak', false);

        //休憩時間のレコードを取得
        $breakRecords = BreakRecord::all();

        // $breakRecords が空でないかどうかを確認
        if ($breakRecords->isEmpty()) {
            // $breakRecords が空の場合の処理
            $totalBreakTimeFormatted = 'No break records found';
        } else {

        //休憩時間の合計を秒単位で計算
        $totalBreakTimeSeconds = 0;
        foreach ($breakRecords as $breakRecord) {
            //休憩時間を秒単位に変換して合計に加算
            $totalBreakTimeSeconds += $breakRecord->break_time;
        }
        //合計した休憩時間を時間：分：秒の形式に変換
        $totalBreakTimeFormatted = gmdate("H:i:s, $totalBreakTimeSeconds");
    }

        //ビューを表示し、休憩状態を渡す
        return view('index', compact('totalBreakTimeFormatted', 'onBreak'));
    }

    public function breakIn(Request $request)
    {

        // 直近の休憩終了時間がNULLでないか確認し、すでに休憩中であればリダイレクト
        $latestBreakRecord = BreakRecord::where('users_id', auth()->user()->id)
                                     ->latest()
                                     ->first();

        if ($latestBreakRecord && is_null($latestBreakRecord->breakOut)) {
            return redirect()->back()->with('error', '既に休憩中です');
    }

        $breakRecord = new BreakRecord();
        $breakRecord->users_id = auth()->user()->id;
        $userName = auth()->user()->name;
        $message = "{$userName}さんが休憩を開始しました";
        Session::flash('status', $message);
        $breakRecord->breakIn = now();
        $breakRecord->save();

        //セッションに休憩中であることを保存
        session(['onBreak' => true]);
        return redirect()->route('index')->with(['onBreak' => true]);

    }

    public function breakOut(Request $request)
    {

        //セッションから休憩中の状態を取得
        $onBreak = session('onBreak', false);

        //休憩中であれば退勤ボタンを無効化
        if ($onBreak) {
            //休憩中の場合は適切な処理をおこなう
            
            return redirect()->back()->with('error', '休憩中には退勤できません');
        }

        //休憩終了の処理を実行
        
        $breakRecord = BreakRecord::where('users_id', auth()->user()->id)
                                    ->latest()
                                    ->first();
        if ($breakRecord) {

        $userName = auth()->user()->name;
        $message = "{$userName}さんが休憩を終了しました";
        Session::flash('status', $message);
        $breakRecord->breakOut = now();
        $breakRecord->save();

        }

        // 休憩終了後に再度休憩中かどうかを取得する
        $onBreak = session('onBreak', false);

        //休憩ボタンまたは休憩開始ボタンを押せるようにする
        session(['onBreak' => false]);
        return redirect()->back();


    }
}
