<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateAdRequest extends FormRequest
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
            'title' => 'required|max:100',
            'content' => 'required|max:800|min:20',
            'contact' => 'required|max:100',
            'image_url' => 'nullable|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            //
        ];
    }

    public function messages()
    {
        return [
            'image_url.file' => 'Supplied item is not a file',
            'image/_url.mimes' => 'File is not of a valid format',
            'image_url.size' => 'File is too big',
            'image_url.image' => 'Supplied item is not an image',
            'image_url.*' => 'There was an issue with your file',
        ];
    }
}
