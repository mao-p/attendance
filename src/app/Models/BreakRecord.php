<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BreakRecord extends Model
{
    use HasFactory;

    protected $table = 'breaks';

    protected $fillable = [
        'users_id', 'breakIn', 'breakOut'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');

    }
}
