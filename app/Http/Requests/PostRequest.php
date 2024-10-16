<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'cover_image' => 'sometimes|required|image',
            'pinned' => 'required|boolean',
            'tags' => 'required|array',
        ];
    }
}

