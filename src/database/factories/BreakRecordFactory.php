<?php
namespace Database\Factories;

use App\Models\BreakRecord;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class BreakRecordFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BreakRecord::class;

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

        // 休憩開始時刻を出勤時刻からランダムに生成
        $breakIn = $date->copy()->addHours(rand(9, 12))->addMinutes(rand(0, 59))->addSeconds(rand(0, 59));
        
        // 休憩終了時刻を休憩開始時刻からランダムに生成
        $breakOut = $breakIn->copy()->addHours(rand(1, 2))->addMinutes(rand(0, 59))->addSeconds(rand(0, 59));

        // 休憩データを生成
        $breakRecord = BreakRecord::create([
            'users_id' => $userId,
            'breakIn' => $breakIn,
            'breakOut' => $breakOut,
            'date' => $date->format('Y-m-d'),
        ]);

        return [
            // 何も返さない（Factory 側で自動的にデータが生成されるため）
        ];
    }
}
