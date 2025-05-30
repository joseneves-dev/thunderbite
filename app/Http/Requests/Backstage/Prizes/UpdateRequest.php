<?php

namespace App\Http\Requests\Backstage\Prizes;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'description' => 'sometimes',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'weight' => 'required|numeric|between:0.01,99.99',
            'starts_at' => 'required|date_format:d-m-Y H:i:s',
            'ends_at' => 'required|date_format:d-m-Y H:i:s',
            'segment' => 'required|in:low,med,high',
        ];
    }
}
