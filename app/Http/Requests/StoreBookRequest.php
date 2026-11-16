<?php

namespace App\Http\Requests;

use App\Enums\BookStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'     => ['required', 'string', 'max:255'],
            'author'    => ['required', 'string', 'max:255'],
            'isbn'      => ['nullable', 'string', 'max:20'],
            'pages'     => ['required', 'integer', 'min:1', 'max:5000'],
            'synopsis'  => ['nullable', 'string', 'max:2000'],
            'condition' => ['required', Rule::in(['baik', 'cukup', 'usang'])],
            'status'    => ['nullable', Rule::in([BookStatus::Available->value, BookStatus::Hidden->value])],
            'genres'    => ['required', 'array', 'min:1', 'max:3'],
            'genres.*'  => ['integer', 'exists:genres,id'],
            'cover'     => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }
}