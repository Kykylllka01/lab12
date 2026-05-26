@extends('layouts.app')

@section('title', 'Регистрация')

@section('content')
<div class="main">
    <div class="row">
        <div class="row--small">
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <h2>Форма регистрации</h2>
                
                <div class="form-group">
                    <label>ФИО</label>
                    <input type="text" name="name" value="{{ old('name') }}" required 
                           style="width: 100%; padding: 10px; margin: 5px 0; border: 1px solid #ddd; border-radius: 4px;">
                    @error('name')
                        <span style="color: red; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required 
                           style="width: 100%; padding: 10px; margin: 5px 0; border: 1px solid #ddd; border-radius: 4px;">
                    @error('email')
                        <span style="color: red; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label>Пароль</label>
                    <input type="password" name="password" required 
                           style="width: 100%; padding: 10px; margin: 5px 0; border: 1px solid #ddd; border-radius: 4px;">
                    @error('password')
                        <span style="color: red; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label>Подтверждение пароля</label>
                    <input type="password" name="password_confirmation" required 
                           style="width: 100%; padding: 10px; margin: 5px 0; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                
                <div class="form-group">
                    <label>Номер телефона</label>
                    <input type="tel" name="phone" value="{{ old('phone') }}" required 
                           placeholder="+7XXXXXXXXXX или 8XXXXXXXXXX"
                           style="width: 100%; padding: 10px; margin: 5px 0; border: 1px solid #ddd; border-radius: 4px;">
                    @error('phone')
                        <span style="color: red; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn" style="background: #4CAF50; color: white; padding: 10px 30px; border: none; cursor: pointer; border-radius: 4px;">
                        Отправить
                    </button>
                </div>
                
                <div style="margin-top: 20px;">
                    Уже есть аккаунт? <a href="{{ route('login') }}">Войти</a>
                </div>
            </form>
        </div>
    </div>	
</div>
@endsection
