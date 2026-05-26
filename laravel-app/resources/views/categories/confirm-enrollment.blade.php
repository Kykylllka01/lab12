@extends('layouts.app')

@section('title', 'Подтверждение записи')

@section('content')
<div class="main">
    <div class="row">
        <div class="row--small">
            <h2>Подтверждение записи на мастер-класс</h2>
            
            <div class="confirmation-details" style="background: #f5f5f5; padding: 20px; margin: 20px 0; border-radius: 8px;">
                <p><strong>ФИО участника:</strong> {{ auth()->user()->name }}</p>
                <p><strong>Вид творчества:</strong> {{ $masterClass->category->name }}</p>
                <p><strong>Название мастер-класса:</strong> {{ $masterClass->title }}</p>
                <p><strong>ФИО мастера:</strong> {{ $masterClass->instructor->name }}</p>
                <p><strong>Дата:</strong> {{ $masterClass->date->format('d.m.Y') }}</p>
                <p><strong>Время:</strong> {{ $masterClass->time }} - {{ $masterClass->end_time }}</p>
                <p><strong>Стоимость:</strong> {{ number_format($masterClass->price, 0, '.', ' ') }} руб.</p>
            </div>
            
            <div class="form-group" style="margin-top: 20px;">
                <form action="{{ route('enroll.confirm', $masterClass->id) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn" style="background: #4CAF50; color: white; padding: 10px 30px; border: none; cursor: pointer; border-radius: 4px;">
                        Подтвердить запись
                    </button>
                </form>
                
                <form action="{{ route('enroll.cancel', $masterClass->id) }}" method="POST" style="display: inline; margin-left: 10px;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn" style="background: #f44336; color: white; padding: 10px 30px; border: none; cursor: pointer; border-radius: 4px;">
                        Отмена
                    </button>
                </form>
            </div>
            
            <div style="margin-top: 20px;">
                <a href="{{ route('category.show', $masterClass->category->id) }}">← Вернуться к списку мастер-классов</a>
            </div>
        </div>
    </div>	
</div>
@endsection
