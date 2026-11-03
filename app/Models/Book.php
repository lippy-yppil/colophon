<?php

namespace App\Models;

use App\Enums\BookStatus;
use App\Enums\LoanStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'book_genre');
    }

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }
}