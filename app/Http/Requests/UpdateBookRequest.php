<?php

namespace App\Http\Requests;

class UpdateBookRequest extends StoreBookRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('book'));
    }
}