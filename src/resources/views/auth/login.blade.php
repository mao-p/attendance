@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')
<div class="login__form">
    <h2 class="login-form__heading content__heading">ログイン</h2>
  <form class="form" action="/login" method="post">
    @csrf
    <div class="login-form__group">
      <input class="login-form__input" type="email" name="email" value="{{ old('email') }}" placeholder="メールアドレス">
      <p class="register-form__error-message">
      </p>
    </div>
      
    <div class="login-form__group">
        <input class="login-form__input" type="password" name="password" placeholder="パスワード">
    </div>
      <div class="form__group-content">
        <div class="form__input--text">
          
        </div>
        
      </div>
    </div>
    <div class="form__button">
      
      <button class="form__button-submit" type="submit" href="/">ログイン</button>
    </div>
  </form>
  <div class="register__link">
    <p class="register__link-message">アカウントをお持ちでない方はこちら</p>
    <a class="register__button-submit" href="{{ route('register') }}">会員登録</a>
  </div>
      
</div>

@section('footer')
    <p class="copyright">Atte,inc.</p>
@endsection
@endsection