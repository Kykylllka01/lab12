<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ОчУмелые ручки')</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/responsive.css') }}">
    @stack('styles')
</head>
<body class="@yield('body-class', '')">
    <div class="header">
        <div class="row grid middle between">
            <div class="logo">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('img/logo.png') }}" alt="Логотип">
                </a>
            </div>
            <div class="title">
                Клуб любителей творчества «ОчУмелые ручки»
            </div>
            <div class="auth">
                @auth
                    @if(auth()->user()->isInstructor())
                        <a href="{{ route('cabinet.index') }}" style="margin-right: 15px;">Личный кабинет</a>
                    @else
                        <a href="{{ route('cabinet.user.index') }}" style="margin-right: 15px;">Личный кабинет</a>
                    @endif
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" style="background: none; border: none; cursor: pointer; color: inherit;">Выход</button>
                    </form>
                @else
                    <a href="{{ route('login') }}">Вход</a> / <a href="{{ route('register') }}">Регистрация</a>
                @endauth
            </div>
        </div>
    </div>
    
    <div class="row row--nogutter">
        <div class="menu-burger">
            <div class="burger">
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>	
    </div>
    
    @if(session('success'))
        <div class="row">
            <div class="alert alert-success">{{ session('success') }}</div>
        </div>
    @endif
    
    @if(session('error'))
        <div class="row">
            <div class="alert alert-error">{{ session('error') }}</div>
        </div>
    @endif
    
    @if(session('info'))
        <div class="row">
            <div class="alert alert-info">{{ session('info') }}</div>
        </div>
    @endif
    
    @yield('content')
    
    <div class="row row--nogutter">
        <div class="line"></div>
    </div>
    
    <div class="footer">
        <div class="row">
            <div class="row--small grid between">
                <div class="address">Наш адрес: ВДНХ, 120в</div>
                <div class="tel">Тел: 89123456765</div>
                <div class="copy">(с) Copyright, 2017</div>
            </div>
        </div>
    </div>
    
    @stack('scripts')
</body>
</html>
