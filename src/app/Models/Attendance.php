<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'users_id',
        'clockIn',
        'clockOut',
        'date',
        'created_at',
        'updated_at'
    ];

    protected $dates = [
        'clockIn',
        'clockOut',
    ];

    //ユーザーとの関連付け
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function clockIn()
    {
    return $this->belongsTo(User::class, 'user_id');
    }

    public function clockOut()
    {
    return $this->belongsTo(User::class, 'user_id');
    }


}
