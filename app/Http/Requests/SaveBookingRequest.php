<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveBookingRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }
    
    public function rules(): array {
        return [
            'first_name'      => 'required|string|min:2|max:255|alpha_num',
            'last_name'       => 'required|string|min:2|max:255|alpha_num',
            'departure'       => 'required|date_format:Y-m-d H:i:s',
            'arrival'         => 'required|date_format:Y-m-d H:i:s',
            'price_list_uuid' => 'required|string|uuid',
            'company_uuid'    => 'required|string|uuid',
            'price'           => 'required|numeric|min:0',
            'distance'        => 'required|numeric|min:0',
            'travel_time'     => 'required|numeric|min:0',
            'legs'            => 'required|array',
        ];
    }
}
