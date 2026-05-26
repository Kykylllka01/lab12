@extends('layouts.app')

@section('title', 'Редактировать мастер-класс')

@section('content')
<div class="main">
    <div class="row">
        <div class="row--small">
            <form method="POST" action="{{ route('cabinet.update', $masterClass->id) }}">
                @csrf
                @method('PUT')
                <h2>Редактирование мастер-класса</h2>
                
                <div class="form-group">
                    <label>Вид творчества</label>
                    <select name="category_id" required style="width: 100%; padding: 10px; margin: 5px 0; border: 1px solid #ddd; border-radius: 4px;">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $masterClass->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <span style="color: red; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label>Название мастер-класса</label>
                    <input type="text" name="title" value="{{ old('title', $masterClass->title) }}" required 
                           style="width: 100%; padding: 10px; margin: 5px 0; border: 1px solid #ddd; border-radius: 4px;">
                    @error('title')
                        <span style="color: red; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label>Описание мастер-класса</label>
                    <textarea name="description" rows="5" required 
                              style="width: 100%; padding: 10px; margin: 5px 0; border: 1px solid #ddd; border-radius: 4px;">{{ old('description', $masterClass->description) }}</textarea>
                    @error('description')
                        <span style="color: red; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label>Дата</label>
                    <input type="date" name="date" id="date" value="{{ old('date', $masterClass->date->format('Y-m-d')) }}" required 
                           min="{{ date('Y-m-d') }}"
                           style="width: 100%; padding: 10px; margin: 5px 0; border: 1px solid #ddd; border-radius: 4px;">
                    @error('date')
                        <span style="color: red; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label>Время (мастер-классы длятся 2 часа)</label>
                    <select name="time" id="time" required style="width: 100%; padding: 10px; margin: 5px 0; border: 1px solid #ddd; border-radius: 4px;">
                        @foreach($timeSlots as $slot)
                            <option value="{{ $slot }}" {{ old('time', $masterClass->time) == $slot ? 'selected' : '' }}>
                                {{ $slot }} - {{ date('H:i', strtotime($slot . ' +2 hours')) }}
                            </option>
                        @endforeach
                    </select>
                    <div id="occupied-warning" style="color: red; font-size: 12px; display: none;">
                        Это время уже занято на выбранную дату!
                    </div>
                    @error('time')
                        <span style="color: red; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label>Количество человек в группе</label>
                    <input type="number" name="max_participants" value="{{ old('max_participants', $masterClass->max_participants) }}" 
                           min="1" max="50" required 
                           style="width: 100%; padding: 10px; margin: 5px 0; border: 1px solid #ddd; border-radius: 4px;">
                    @error('max_participants')
                        <span style="color: red; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label>Стоимость мастер-класса (руб.)</label>
                    <input type="number" name="price" value="{{ old('price', $masterClass->price) }}" 
                           min="0" step="0.01" required 
                           style="width: 100%; padding: 10px; margin: 5px 0; border: 1px solid #ddd; border-radius: 4px;">
                    @error('price')
                        <span style="color: red; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn" id="submit-btn"
                            style="background: #2196F3; color: white; padding: 10px 30px; border: none; cursor: pointer; border-radius: 4px;">
                        Сохранить изменения
                    </button>
                </div>
                
                <div style="margin-top: 20px;">
                    <a href="{{ route('cabinet.index') }}">← Вернуться в личный кабинет</a>
                </div>
            </form>
        </div>
    </div>	
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.getElementById('date');
    const timeSelect = document.getElementById('time');
    const occupiedWarning = document.getElementById('occupied-warning');
    const submitBtn = document.getElementById('submit-btn');
    
    async function checkOccupiedSlots() {
        if (!dateInput.value) return;
        
        try {
            const response = await fetch(`{{ route('cabinet.slots') }}?date=${dateInput.value}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const data = await response.json();
            
            // Блокируем занятые слоты (кроме текущего времени если дата не изменилась)
            const options = timeSelect.options;
            for (let i = 0; i < options.length; i++) {
                const option = options[i];
                if (data.occupied.includes(option.value)) {
                    option.disabled = true;
                    option.text = option.text + ' (занято)';
                } else {
                    option.disabled = false;
                    if (option.text.includes('(занято)')) {
                        option.text = option.text.replace(' (занято)', '');
                    }
                }
            }
            
            if (data.occupied.includes(timeSelect.value)) {
                occupiedWarning.style.display = 'block';
                submitBtn.disabled = true;
            }
        } catch (error) {
            console.error('Ошибка проверки слотов:', error);
        }
    }
    
    dateInput.addEventListener('change', checkOccupiedSlots);
    
    timeSelect.addEventListener('change', function() {
        if (this.value && !this.disabled) {
            occupiedWarning.style.display = 'none';
            submitBtn.disabled = false;
        }
    });
});
</script>
@endpush
@endsection
