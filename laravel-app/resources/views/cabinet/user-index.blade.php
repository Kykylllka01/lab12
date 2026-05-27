@extends('layouts.app')

@section('title', 'Личный кабинет участника')
@section('body-class', 'dp')

@section('content')
<div class="main">
    <div class="row">
        <div class="hover"></div>
        <div class="title"></div>
        
        <div class="row--small grid between">
            <div class="content driver-page">
                <div class="driver-page-photo">
                    <img src="{{ asset('img/driver-page.png') }}" alt="{{ $user->name }}">
                </div>	
                <div class="driver-page-name">{{ $user->name }}</div>
                
                <div class="driver-page-text">
                    <div class="driver-page-my">Мои мастер-классы</div>
                    
                    @if($enrollments->count() > 0)
                        <table class="driver-page-table">
                            <tbody>
                                @foreach($enrollments as $enrollment)
                                    <tr>
                                        <td>
                                            {{ $enrollment->masterClass->date->format('d.m.Y') }} {{ $enrollment->masterClass->time }}<br>
                                            <small>{{ $enrollment->masterClass->category->name }}</small>
                                        </td>
                                        <td>
                                            <b>{{ $enrollment->masterClass->title }}</b>
                                            <p>
                                                Ведущий: {{ $enrollment->masterClass->instructor->name }}<br>
                                                Цена: {{ $enrollment->masterClass->price }} руб.
                                            </p>
                                            
                                            <div style="margin-top: 10px;">
                                                <form action="{{ route('cabinet.user.cancel', $enrollment->masterClass->id) }}" method="POST" style="display: inline;"
                                                      onsubmit="return confirm('Вы уверены, что хотите отменить запись на этот мастер-класс?');">
                                                    @csrf
                                                    @method('POST')
                                                    <button type="submit" style="color: #f44336; background: none; border: none; cursor: pointer;">Отменить запись</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>Вы пока не записаны ни на один мастер-класс.</p>
                    @endif
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
