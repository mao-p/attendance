  <?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\TimeClockController;
use App\Http\Controllers\BreakController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\TimeRecordController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthenticatedSessionController::class, 'login'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'logout'])->name('logout');


Route::get('/auth/register', [RegisteredUserController::class, 'create'])->name('register.auth');
Route::post('/auth/register', [RegisteredUserController::class, 'store']);

Route::middleware('auth')->group(function () {
    Route::get('/', [AuthController::class, 'index']);
});


Route::get('/index', [TimeClockController::class, 'index'])->name('index');

Route::post('/clock/in', [TimeClockController::class, 'clockIn'])->name('clock.in');
Route::post('/clock/out', [TimeClockController::class, 'clockOut'])->name('clock.out');

Route::post('/break/in', [BreakController::class, 'breakIn'])->name('break.in');
Route::post('/break/out', [BreakController::class, 'breakOut'])->name('break.out');

Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
Route::get('/show-previous-day/{date}', [AttendanceController::class, 'showPreviousDay'])->name('show-previous-day');
Route::get('/show-next-day/{date}', [AttendanceController::class, 'showNextDay'])->name('show-next-day');
Route::get('/total-work-time', [AttendanceController::class, 'totalWorkTime'])->name('total-work-time');
Route::get('/total-break-time', [AttendanceController::class, 'totalBreakTime'])->name('total-break-time');