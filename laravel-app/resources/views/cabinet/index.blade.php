@extends('layouts.app')

@section('title', 'Личный кабинет')
@section('body-class', 'dp')

@section('content')
<div class="main">
    <div class="row">
        <div class="hover"></div>
        <div class="title"></div>
        
        <div class="row--small grid between">
            <div class="content driver-page">
                <div class="driver-page-photo">
                    <img src="{{ asset('img/driver-page.png') }}" alt="{{ $instructor->name }}">
                </div>	
                <div class="driver-page-name">{{ $instructor->name }}</div>
                
                <div class="driver-page-text">
                    <div class="driver-page-my">Мои мастер-классы</div>
                    
                    @if($masterClasses->count() > 0)
                        <table class="driver-page-table">
                            <tbody>
                                @foreach($masterClasses as $mc)
                                    <tr>
                                        <td>
                                            {{ $mc->date->format('d.m.Y') }} {{ $mc->time }}<br>
                                            <small>{{ $mc->category->name }}</small>
                                        </td>
                                        <td>
                                            <b>{{ $mc->title }}</b>
                                            <p>
                                                @if($mc->enrollments->count() > 0)
                                                    @foreach($mc->enrollments as $enrollment)
                                                        {{ $loop->iteration }}. {{ $enrollment->user->name }} ({{ $enrollment->user->created_at->format('d.m.Y') }})<br>
                                                        email: {{ $enrollment->user->email }}<br>
                                                        tel: {{ $enrollment->user->phone }}<br><br>
                                                    @endforeach
                                                @else
                                                    Нет участников
                                                @endif
                                            </p>
                                            
                                            <div style="margin-top: 10px;">
                                                <a href="{{ route('cabinet.edit', $mc->id) }}" 
                                                   style="color: #2196F3; margin-right: 15px;">Редактировать</a>
                                                <form action="{{ route('cabinet.destroy', $mc->id) }}" method="POST" style="display: inline;"
                                                      onsubmit="return confirm('Вы уверены, что хотите удалить этот мастер-класс?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" style="color: #f44336; background: none; border: none; cursor: pointer;">Удалить</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>У вас пока нет созданных мастер-классов.</p>
                    @endif
                </div>
                
                <div class="driver-page-btn-wrapper">
                    <a href="{{ route('cabinet.create') }}" class="driver-page-btn btn" 
                       style="display: inline-block; padding: 10px 20px; background: #4CAF50; color: white; text-decoration: none; border-radius: 4px;">
                        Добавить мастер-класс
                    </a>
                </div>
            </div>
            
            <ul class="menu">
                @php
                    $categories = \App\Models\Category::all();
                @endphp
                @foreach($categories as $category)
                    <li><a href="{{ route('category.show', $category->id) }}">{{ $category->name }}</a></li>
                @endforeach
            </ul>
        </div>
    </div>	
</div>
@endsection
