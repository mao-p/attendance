<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\BreakRecord;

class AuthController extends Controller
{
    public function index()
    {
        $user_id = auth()->id();
        $clockedIn = Attendance::where('users_id', $user_id)->exists();
        $onBreak = BreakRecord::where('users_id', $user_id)->whereNull('breakOut')->exists();

        return view('index', [
            'clockedIn' => $clockedIn,
            'onBreak' => $onBreak,
        ]);
    }
}