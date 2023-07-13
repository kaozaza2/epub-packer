<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'book_title' => ['nullable', 'string'],
            'book_description' => ['nullable', 'string'],
            'book_creator' => ['nullable', 'string'],
            'book_subject' => ['nullable', 'string'],
            'book_publisher' => ['nullable', 'string'],
            'book_language' => ['nullable', 'string'],
            'book_width' => ['required', 'numeric'],
            'book_height' => ['required', 'numeric'],
        ];
    }
}
