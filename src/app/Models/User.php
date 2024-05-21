<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isClockedIn()
    {
    // ユーザーの最新の出勤記録を取得
    $latestAttendance = $this->attendances()->latest()->first();

    // 最新の出勤記録が存在しない場合は出勤していないと判断
    if (!$latestAttendance) {
        return false;
    }

    // 出勤している場合は、clockOutがnullであるかどうかを確認
    return $latestAttendance->clockOut === null;
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'users_id');
    }

        public function breaks(): HasMany
    {
        return $this->hasMany(BreakRecord::class, 'users_id');
    }

}