@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('content')

<div class="register__form">
    <h2 class="register-form__heading content__heading">会員登録</h2>

    <form class="form" action="/auth/register" method="post">
    @csrf
    <div class="register-form__group">
        <input class="register-form__input @error('name') is-invalid @enderror" type="text" name="name" id="name" value="{{ old('name') }}" placeholder="お名前">
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        </input>
    </div>

    <div class="register-form__group">
        <input class="register-form__input @error('email') is-invalid @enderror" type="email" name="email" id="email" value="{{ old('email') }}" placeholder="メールアドレス">
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="register-form__group">
        <input class="register-form__input @error('password') is-invalid @enderror" type="password" name="password" id="password" value="{{ old('password') }}" placeholder="パスワード">
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="register-form__group">
        <input class="register-form__input" type="password" name="password_confirmation" id="password_confirmation" placeholder="確認用パスワード"></input>
        <div class="form__button">
      <button class="form__button-submit" type="submit">登録</button>
    </div>
    </form>
    
    <div class="login__link">
    <p class="register__link-message">アカウントをお持ちの方はこちら</p>
    <a class="login__button-submit" href="/login">ログイン</a>
        </div>
    </div>
</div>
@section('footer')
<p class="copyright">Atte,inc.</p>
@endsection
@endsection