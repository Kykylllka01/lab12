@extends('layouts.app')

@section('title', $category->name)

@section('content')
<div class="main">
    <div class="row">
        <div class="hover"></div>
        <div class="title">{{ $category->name }}</div>
        
        <div class="row--small grid between">
            <div class="content">
                @if($category->description)
                    <img src="{{ asset('img/elifant.png') }}" alt="{{ $category->name }}">
                    <p>{{ $category->description }}</p>
                @endif
            </div>
            
            <ul class="menu">
                @foreach($categories as $cat)
                    <li><a href="{{ route('category.show', $cat->id) }}">{{ $cat->name }}</a></li>
                @endforeach
            </ul>
        </div>

        <div class="row shedule">
            <div class="row--small">
                <h2>Расписание</h2>
                <div class="drivers">
                    @forelse($category->masterClasses as $masterClass)
                        <div class="driver grid">
                            <div class="driver-left grid">
                                <div class="driver-photo">
                                    <img src="{{ asset('img/driver1.png') }}" alt="{{ $masterClass->instructor->name }}">
                                </div>
                                <div class="driver-text">
                                    <div class="driver-name">{{ $masterClass->instructor->name }}</div>
                                    <div class="driver-desc">
                                        <b>{{ $masterClass->title }}</b><br>
                                        {{ Str::limit($masterClass->description, 150) }}
                                    </div>
                                </div>
                            </div>
                            <div class="driver-right">
                                @auth
                                    @if(auth()->user()->isInstructor())
                                        <span style="color: #999;">Только для посетителей</span>
                                    @else
                                        <a href="{{ route('enroll.show', $masterClass->id) }}" class="driver-btn" style="display: inline-block; padding: 10px 20px; background: #4CAF50; color: white; text-decoration: none; border-radius: 4px;">записаться</a>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="driver-btn" style="display: inline-block; padding: 10px 20px; background: #2196F3; color: white; text-decoration: none; border-radius: 4px;">войти для записи</a>
                                @endauth
                                <div class="driver-time">
                                    {{ $masterClass->date->format('d.m.Y') }} {{ $masterClass->time }}<br>
                                    Свободных мест: {{ $masterClass->availableSeats() }} из {{ $masterClass->max_participants }}<br>
                                    Стоимость: {{ number_format($masterClass->price, 0, '.', ' ') }} руб.
                                </div>	
                            </div>	
                        </div>
                    @empty
                        <p>На данный момент мастер-классов по этому направлению нет.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>	
</div>
@endsection
