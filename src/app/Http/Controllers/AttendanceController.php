<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use App\Models\BreakRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;


class AttendanceController extends Controller
{

    public function index(Request $request)
    {

        // 日付を取得
        $date = $request->input('date', Carbon::today()->toDateString());
        $currentDate = Carbon::parse($date);


        //前日と次の日を設定
        $previousDate = $currentDate->copy()->subDay();
        $nextDate = $currentDate->copy()->addDay();

        //本日の出勤記録があるユーザーを取得
        $usersWithAttendances = User::whereHas('attendances', function ($query) use ($currentDate) {
            $query->whereDate('clockIn', $currentDate->toDateString());
        })->get();

        //本日の休憩レコードがあるユーザーを取得
        $usersWithBreaks = User::whereHas('breaks', function ($query) use ($currentDate) {
            $query->whereDate('breakIn', $currentDate->toDateString());
        })->get();

        $users = $usersWithBreaks->concat($usersWithAttendances);

        //各ユーザーごとに休憩時間と勤務時間の計算
        $userData = []; //配列

        foreach ($usersWithBreaks as $user) {
                    $attendances = $user->attendances()
                        ->whereDate('clockIn', $currentDate->toDateString())
                        ->with(['clockIn', 'clockOut'])
                        ->get();
                    $breakRecords = $user->breaks()
                        ->whereDate('breakIn',  $currentDate->toDateString())
                        ->whereNotNull('breakOut')
                        ->get();

        // 出勤記録が存在しない場合は処理をスキップ
            if ($attendances->isEmpty()) {
                continue;
            }

        $totalWorkTimeSeconds = $this->calculateTotalWorkTime($attendances);
        $totalBreakTimeSeconds = $this->calculateTotalBreakTime($breakRecords);

        //休憩時間を勤務時間から差し引く
        $totalWorkTimeSeconds -= $totalBreakTimeSeconds;



        //ユーザーデータを配列に追加
        $userData[$user->id] = [
            'userAttendance' => $attendances,
            'totalWorkTime' => gmdate("H:i:s", $totalWorkTimeSeconds),
            'totalBreakTime' => gmdate("H:i:s", $totalBreakTimeSeconds),
        ];
    }

        // 手動でページネーションを作成
        $page = Paginator::resolveCurrentPage('page');
        $perPage = 5;
        $offset = ($page * $perPage) - $perPage;
        $paginatedItems = array_slice($userData, $offset, $perPage, true);
        $userData = new LengthAwarePaginator($paginatedItems, count($userData), $perPage, $page, [
            'path' => Paginator::resolveCurrentPath(),
    ]);


        return view('attendance', [
            'userData' => $userData,
            'currentDate' => $currentDate->toDateString(),
            'previousDate' => $previousDate->toDateString(),
            'nextDate' => $nextDate->toDateString(),
            //'totalWorkTimeMinutes' => $totalWorkTimeMinutes,
        ]);
    }




    //勤務時間を計算するメソッド
    private function calculateTotalWorkTime($attendances)
    {

    //勤務時間合計
    $totalWorkTimeSeconds = 0;

    //各出勤記録から勤務時間を計算して合計する
    foreach ($attendances as $attendance) {

        $clockInTime = Carbon::parse($attendance->clockIn);
        $clockOutTime = Carbon::parse($attendance->clockOut);

         //出勤と退勤時間が存在しない場合スキップ
        if (!$clockInTime || !$clockOutTime) {
            continue;
        }

        $totalWorkTimeSeconds += $clockOutTime->diffInSeconds($clockInTime);
    }

    return $totalWorkTimeSeconds;
    }


    //  休憩時間を計算するメソッド
    private function calculateTotalBreakTime($breakRecords)
    {

        //休憩時間の合計を初期化
        $totalBreakTimeSeconds = 0;

        //休憩レコードの各レコードから休憩時間を取得して合計する
        foreach ($breakRecords as $breakRecord) {
            //休憩開始と休憩終了時間をCarbonオブジェクトに変換
            $breakInTime = Carbon::parse($breakRecord->breakIn);
            $breakOutTime = Carbon::parse($breakRecord->breakOut);

            //
            if ($breakInTime && $breakOutTime) {
                $totalBreakTimeSeconds += $breakOutTime->diffInSeconds($breakInTime);
            }


        }

        return $totalBreakTimeSeconds;
    }

    public function showPreviousDay($date)
    {
        $previousDate = Carbon::parse($date)->subDay()->toDateString();
        return redirect()->route('attendance.index', ['date' => $previousDate]);
    }

    public function showNextDay($date)
    {
        $nextDate = Carbon::parse($date)->addDay()->toDateString();
        return redirect()->route('attendance.index', ['date' => $nextDate]);
    }

}