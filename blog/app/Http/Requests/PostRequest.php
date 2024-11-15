<?php

// app/Http/Requests/PostRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;  // Allow all users to make this request (adjust as necessary)
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',  // Optional image validation
        ];
    }

    /**
     * Customize the error messages.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'The post name is required.',
            'content.required' => 'Content cannot be empty.',
            'image.image' => 'The uploaded file must be an image.',
            'image.mimes' => 'The image must be of type jpeg, png, jpg, gif, or svg.',
            'image.max' => 'The image size must not exceed 2MB.',
        ];
    }
}
