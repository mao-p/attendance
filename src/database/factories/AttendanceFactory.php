<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;

class AttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

// ランダムなユーザーIDを取得
        $userId = User::inRandomOrder()->first()->id;

        // 開始日付を設定（2024年5月21日）
        $startDate = Carbon::create(2024, 5, 21);

        // 1日ずつ遡ってデータを生成
        $date = $startDate->copy()->subDays($this->faker->numberBetween(0, 6));

        // 出勤時刻をランダムに生成
        $clockIn = $date->copy()->addHours(rand(8, 10))->addMinutes(rand(0, 59))->addSeconds(rand(0, 59));
        
        // 退勤時刻を生成（出勤時刻から最大8時間後）
        $clockOut = $clockIn->copy()->addHours(rand(8, 12))->addMinutes(rand(0, 59))->addSeconds(rand(0, 59));

        // 出勤データを生成
        $attendance = Attendance::create([
            'users_id' => $userId,
            'clockIn' => $clockIn,
            'clockOut' => $clockOut,
            'date' => $date->format('Y-m-d'),
        ]);

        // 出勤後のランダムな時間に休憩を設定
        $breakIn = $clockIn->copy()->addHours(rand(2, 4))->addMinutes(rand(0, 59));
        $breakOut = $breakIn->copy()->addMinutes(rand(10, 30));

        // 休憩データを生成
        BreakRecord::create([
            'users_id' => $userId,
            'breakIn' => $breakIn,
            'breakOut' => $breakOut,
            'date' => $date->format('Y-m-d'),
        ]);

        // 退勤後のランダムな時間に退勤を設定
        $clockOut = $breakOut->copy()->addHours(rand(1, 3))->addMinutes(rand(0, 59))->addSeconds(rand(0, 59));
        $attendance->update(['clockOut' => $clockOut]);

        return [
            // 何も返さない（Factory 側で自動的にデータが生成されるため）
        ];
    }
}
