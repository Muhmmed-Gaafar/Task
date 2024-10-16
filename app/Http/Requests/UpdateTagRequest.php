<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTagRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|unique:tags,name,' . $this->tag->id,
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The tag name is required.',
            'name.unique' => 'The tag name must be unique.',
        ];
    }
}
