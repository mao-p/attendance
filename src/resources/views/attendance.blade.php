
@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance.css') }}">
<link rel="stylesheet" href="{{ asset('css/common.css') }}">
@endsection

@section('link')
<form action="logout" method="post">
  @csrf
  <div class="header-link__group">
  <a class="header-link" href="/">ホーム</a>
  <a class="header-link" href="/attendance">日付一覧</a>
  <a class="header-link" href="/login">ログアウト</a>
  </div>
</form>
@endsection

@section('content')
<div class="date-navigation">
<form method="get" action="{{ route('show-previous-day', ['date' => $currentDate]) }}">
    @csrf
    <button type="submit">&lt;</button>
</form>

<p>{{ $currentDate ?? '' }}</p>

<form method="get" action="{{ route('show-next-day', ['date' => $currentDate]) }}">
    @csrf
    <button type="submit">&gt;</button>
</form>
</div>

<div class="table-container">
<table>
    <thead>
        <tr>
            <th>名前</th>
            <th>勤務開始</th>
            <th>勤務終了</th>
            <th>休憩時間</th>
            <th>勤務時間</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($userData ?? [] as $userId => $userAttendanceData)
            
                <tr>
                    <td>{{ optional($userAttendanceData['userAttendance']->first()->user)->name }}</td>
                    <td>{{ $userAttendanceData['userAttendance']->first()->clockIn ? $userAttendanceData['userAttendance']->first()->clockIn->format('H:i:s') : '' }}</td>
                    <td>{{ $userAttendanceData['userAttendance']->first()->clockOut ? $userAttendanceData['userAttendance']->first()->clockOut->format('H:i:s') : '' }}</td>
                    <td>{{ $userAttendanceData['totalBreakTime'] }} </td>
                    <td>{{ $userAttendanceData['totalWorkTime'] }} </td>
                </tr>
        @endforeach

    </tbody>
</table>
<div class="pagination-links">
{{ $userData->links() }}
</div>

</div>
@endsection

@section('footer')
  <p class="copyright">Atte,inc.</p>
@endsection
