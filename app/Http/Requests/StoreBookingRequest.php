<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            //
            "flight_id" => ['required'],
            "email"=> ['required'],
            'passenger' => ['required', 'max:255'],
            'contact' => ['required'],
            'class' =>['required'],
            'price' => ['required']
        ];
    }
}
