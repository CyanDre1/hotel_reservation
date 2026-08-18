<?php

namespace App\Http\Requests;

use App\Models\Booking;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'room_id' => ['required', 'integer', 'exists:rooms,id'],
            'check_in' => ['required', 'date', 'after_or_equal:today'],
            'check_out' => ['required', 'date', 'after:check_in'],
        ];
    }

    /**
     * Configure additional validation after the primary rules pass.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $roomId = $this->integer('room_id');
            $checkIn = $this->date('check_in');
            $checkOut = $this->date('check_out');

            if ($roomId && $checkIn && $checkOut) {
                $conflict = Booking::query()
                    ->where('room_id', $roomId)
                    ->whereNotIn('status', ['cancelled', 'checked_out'])
                    ->where('check_in', '<', $checkOut)
                    ->where('check_out', '>', $checkIn)
                    ->exists();

                if ($conflict) {
                    $validator->errors()->add('check_in', 'Kamar tidak tersedia pada tanggal yang dipilih.');
                }
            }
        });
    }
}
