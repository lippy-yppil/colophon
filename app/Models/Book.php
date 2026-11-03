<?php

namespace App\Models;

use App\Enums\BookStatus;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'owner_id', 'title', 'author', 'isbn', 'pages',
        'synopsis', 'cover_path', 'condition', 'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => BookStatus::class,
        ];
    }
}