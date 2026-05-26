@extends('layouts.app')

@section('title', 'Главная страница')

@section('content')
<div class="main">
    <div class="row">
        <div class="hover"></div>
        <div class="title">Добро пожаловать в клуб «ОчУмелые ручки»!</div>
        
        @if($userEnrollments && $userEnrollments->count() > 0)
            <div class="user-enrollments">
                <h2>Ваши записи на мастер-классы</h2>
                <table class="driver-page-table">
                    <tbody>
                        @foreach($userEnrollments as $enrollment)
                            <tr>
                                <td>
                                    {{ $enrollment->masterClass->date->format('d.m.Y') }} 
                                    {{ $enrollment->masterClass->time }}
                                </td>
                                <td>
                                    <b>{{ $enrollment->masterClass->category->name }}</b><br>
                                    {{ $enrollment->masterClass->title }}<br>
                                    Ведущий: {{ $enrollment->masterClass->instructor->name }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        
        <div class="row--small grid between">
            <div class="content">
                <p>Клуб любителей творчества «ОчУмелые ручки» приглашает вас на увлекательные мастер-классы по различным видам творчества!</p>
                <p>Выберите интересующее вас направление из меню слева и запишитесь на мастер-класс.</p>
            </div>
            
            <ul class="menu">
                @foreach($categories as $category)
                    <li><a href="{{ route('category.show', $category->id) }}">{{ $category->name }}</a></li>
                @endforeach
            </ul>
        </div>
    </div>	
</div>
@endsection
