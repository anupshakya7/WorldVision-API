<?php

namespace App\Http\Requests\Admin\Country;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CountryRequest extends FormRequest
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
            'country' => 'required|string|max:255',
            'country_code' => 'nullable|string|max:3',
            'geometry' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'bounding_box' => 'nullable|string',
        ];


        //If it's Store (POST) request
        if ($this->isMethod('post')) {
            $rules['parent_id'] = 'nullable|exists:countries,id';
        }

        //If it's Update (PUT/PATCH) request
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules['parent_id'] = ['nullable','exists:countries,id',Rule::notIn([$this->route('country')])];
        }

        return $rules;
    }

    public function attributes()
    {
        return[
            'country' => 'Country Title',
            'country_code' => 'Country Code',
            'geometry' => 'Geometry',
            'parent_id' => 'Parent Country',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'bounding_box' => 'Bounding Box',
        ];
    }
}
