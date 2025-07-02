<?php

namespace App\Http\Requests;

use App\Http\Controllers\MenuController;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MenuIndexRequest extends FormRequest
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
            'keyword' => ['nullable', 'string',  'max:255'],
            'sort_type' => ['nullable', 'string', Rule::in(array_keys(MenuController::SORT_LIST))],
            'author' => ['nullable', 'string', Rule::exists('users', 'name')],
            'tag_ids' => ['nullable', 'array'],
            'tag_ids.*' => ['numeric', Rule::exists('tags', 'id')],
        ];
    }

    public function validationData()
    {
        return $this->query();
    }
}
