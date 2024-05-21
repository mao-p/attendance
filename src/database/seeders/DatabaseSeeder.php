<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\User;
use App\Models\BreakRecord;
use App\Models\Attendance;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        // ユーザーを作成
        User::factory()->count(10)->create();

        // すべてのユーザーを取得
        $users = User::all();

        // 開始日付を設定（例として、2024年5月21日）
        $startDate = Carbon::create(2024, 5, 21);

        // 生成する日数
        $daysToGenerate = 7;

        // 各ユーザーに対して指定された日数分のダミーデータを生成
        foreach ($users as $user) {
            for ($i = 0; $i < $daysToGenerate; $i++) {
                // 日付を設定
                $currentDate = $startDate->copy()->subDays($i);

                // 出勤レコードを生成
                $attendance = new Attendance();
                $attendance->users_id = $user->id;
                $attendance->clockIn = $currentDate->copy()->setHour(9)->setMinute(0)->setSecond(0);
                $attendance->save();

                // 休憩レコードを生成（例として、出勤後のランダムな時刻から1時間後まで）
                $breakIn = $attendance->clockIn->copy()->addMinutes(rand(30, 60));
                $breakOut = $breakIn->copy()->addMinutes(rand(10, 30));

                $breakRecord = new BreakRecord();
                $breakRecord->users_id = $user->id;
                $breakRecord->breakIn = $breakIn;
                $breakRecord->breakOut = $breakOut;
                $breakRecord->save();

                // 退勤レコードを生成（休憩終了後の30分後とする）
                $clockOut = $breakOut->copy()->addMinutes(30);

                $attendance->clockOut = $clockOut;
                $attendance->save();
            }
        }
    }
}
