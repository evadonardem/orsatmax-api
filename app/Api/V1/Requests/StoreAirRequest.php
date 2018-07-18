<?php

namespace App\Api\V1\Requests;

use Dingo\Api\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAirRequest extends FormRequest
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
          'component_name' => 'required|unique:airs_list',
          'alias' => 'nullable',
          'aqi_no' => 'required',
          'carbon_no' => 'required|numeric',
          'cas' => 'required'
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'component_name.required' => 'Air name is required.',
            'component_name.unique' => 'Air name already exist.'
        ];
    }
}
