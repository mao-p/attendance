@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
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
<div class="attendance__alert">
  @if(Auth::check())
  <p>
  {{ Auth::user()->name }}さんおはようございます</p>
  @else
  <p>ログインしていません</p>
  @endif
  @if(session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif

</div>


<div class="attendance__content">
  <div class="attendance__panel">
    <!--出勤ボタン-->
    <form class="attendance_button" method="post" action="{{ route('clock.in') }}">
      @csrf
      @if(!isset($isClockInDisabled) || !$isClockInDisabled)
      <button type="submit" class="btn-clock-in">勤務開始</button>
      @else
        <button type="submit" class="btn-clock-in" disabled>勤務開始</button>
      @endif
    </form>

    <!--休憩開始ボタン-->
   

    <form method="post" action="{{ route('break.in') }}">
      @csrf
      <button type="submit" class="btn-break-in" {{ isset($onBreak) && $onBreak ? 'disabled' : '' }}>休憩開始</button>
    </form>

    <!--休憩終了ボタン-->
    <form method="post" action="{{ route('break.out') }}">
      @csrf
      <button type="submit" class="btn-break-out" {{ !isset($onBreak) || !$onBreak ? 'disabled' : '' }}>休憩終了</button>
    </form>

    <!--退勤ボタン-->
    @if($onBreak ?? false) <!--休憩中の場合 -->
    <form method="post" action="{{ route('clock.out') }}">
      @csrf
      <button type="submit" class="btn-clock-out btn-disabled" disabled>退勤</button>
    </form>
    @else <!-- 休憩中でない場合 -->
    <form method="post" action="{{ route('clock.out') }}">
      @csrf
      <button type="submit" class="btn-clock-out" {{ isset($isClockedOut) && $isClockedOut ? 'disabled' : '' }}>退勤</button>
    </form>
    @endif
  </div>
</div>
@endsection

@section('footer')
  <p class="copyright">Atte,inc.</p>
@endsection
