<?php

namespace App\Http\Requests\Site;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreFavoriteArrayRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'car_id'   => 'required|array', // Ensure it's an array
            'car_id.*' => 'required|exists:cars,id', // Validate each car_id in the array
            ];
    }
}
