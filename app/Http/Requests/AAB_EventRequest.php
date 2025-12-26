<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AAB_EventRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'start_date' => ['required', 'date', 'after:now'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'place' => ['required', 'string', 'max:255'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'category_id' => ['required', 'exists:aab_categories,id'],
            'capacity' => ['required', 'integer', 'min:1'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'is_free' => ['nullable', 'boolean'],
            'status' => ['nullable', 'in:active,archived'],
        ];
    }
}
