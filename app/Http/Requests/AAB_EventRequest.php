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
            'is_free' => ['nullable', 'boolean'],
            'price' => $this->getPriceRules(),
            'category_id' => ['required', 'exists:aab_categories,id'],
            'capacity' => ['required', 'integer', 'min:1'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'status' => ['nullable', 'in:active,archived'],
        ];
    }

    /**
     * Get price validation rules based on is_free field.
     * If event is free, price can be 0 or null.
     * If event is not free, price must be greater than 0.
     */
    protected function getPriceRules(): array
    {
        if ($this->boolean('is_free')) {
            return ['nullable', 'numeric', 'min:0'];
        }

        return ['required', 'numeric', 'gt:0'];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'price.required' => 'A price is required for paid events.',
            'price.gt' => 'The price must be greater than 0 for paid events. Use the "Free Event" option for free events.',
        ];
    }
}
