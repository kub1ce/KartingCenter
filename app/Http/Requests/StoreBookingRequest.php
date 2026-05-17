<?php

namespace App\Http\Requests;

use App\Models\KartType;
use App\Models\TimeSlot;
use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'time_slot_id' => ['required', 'integer', 'exists:time_slots,id'],
            'participants_count' => ['required', 'integer', 'min:1', 'max:100'],
            'karts' => ['required', 'array', 'min:1'],
            'karts.*.kart_type_id' => ['required', 'integer', 'exists:kart_types,id'],
            'karts.*.quantity' => ['required', 'integer', 'min:0'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            $karts = $this->input('karts', []);
            $hasAnyKart = collect($karts)->contains(fn($k) => (int)($k['quantity'] ?? 0) > 0);
            if (!$hasAnyKart) {
                $v->errors()->add('karts', 'Выберите хотя бы один карт (количество > 0).');
                return;
            }

            $slot = TimeSlot::with('track')->find($this->time_slot_id);

            // Слот существует и не заблокирован
            if (!$slot || $slot->is_blocked) {
                $v->errors()->add('time_slot_id', 'Выбранный слот недоступен или заблокирован.');
                return;
            }

            // Слот ещё не прошёл
            if ($slot->date < today()) {
                $v->errors()->add('time_slot_id', 'Выбранный слот уже прошёл.');
                return;
            }

            // Слот не занят
            if ($slot->bookings()->whereIn('status', ['Pending', 'Confirmed'])->exists()) {
                $v->errors()->add('time_slot_id', 'К сожалению, этот слот уже забронирован.');
                return;
            }

            // Лимит трассы
            $participants = (int)$this->participants_count;
            if ($participants > $slot->track->max_participants) {
                $v->errors()->add(
                    'participants_count',
                    "Превышен лимит трассы: максимум {$slot->track->max_participants} участников."
                );
            }

            // Суммарная вместимость выбранных картов >= количества участников
            $totalSeats = collect($karts)->sum(function ($k) {
                if ((int)($k['quantity'] ?? 0) <= 0) return 0;
                $type = KartType::find($k['kart_type_id']);
                return $type ? $type->seats * (int)$k['quantity'] : 0;
            });

            if ($totalSeats < $participants) {
                $v->errors()->add(
                    'karts',
                    "Недостаточно мест в выбранных картах: {$totalSeats} мест, а участников {$participants}."
                );
            }
        });
    }

    public function messages(): array
    {
        return [
            'time_slot_id.required' => 'Не выбран временной слот.',
            'time_slot_id.exists' => 'Выбранный слот не существует.',
            'participants_count.required' => 'Укажите количество участников.',
            'participants_count.integer' => 'Количество участников должно быть числом.',
            'participants_count.min' => 'Минимум 1 участник.',
            'karts.required' => 'Необходимо выбрать карты.',
            'karts.array' => 'Некорректный формат данных о картах.',
            'karts.*.kart_type_id.required' => 'Тип карта обязателен.',
            'karts.*.kart_type_id.exists' => 'Выбранный тип карта не существует.',
            'karts.*.quantity.required' => 'Укажите количество картов.',
            'karts.*.quantity.integer' => 'Количество картов должно быть числом.',
            'karts.*.quantity.min' => 'Количество картов не может быть отрицательным.',
        ];
    }
}
