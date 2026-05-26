@extends('layouts.app')

@section('title', 'Вход')

@section('content')
<div class="main">
    <div class="row">
        <div class="row--small">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <h2>Форма входа</h2>
                
                @if($errors->has('email'))
                    <div class="alert alert-error" style="color: red; margin-bottom: 15px;">
                        {{ $errors->first('email') }}
                    </div>
                @endif
                
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required style="width: 100%; padding: 10px; margin: 5px 0; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                
                <div class="form-group">
                    <label>Пароль</label>
                    <input type="password" name="password" required style="width: 100%; padding: 10px; margin: 5px 0; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="remember"> Запомнить меня
                    </label>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn" style="background: #2196F3; color: white; padding: 10px 30px; border: none; cursor: pointer; border-radius: 4px;">
                        Войти
                    </button>
                </div>
                
                <div style="margin-top: 20px;">
                    Нет аккаунта? <a href="{{ route('register') }}">Зарегистрироваться</a>
                </div>
            </form>
        </div>
    </div>	
</div>
@endsection
