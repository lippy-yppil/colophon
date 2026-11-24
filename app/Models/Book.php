<?php

namespace App\Models;

use App\Enums\BookStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;

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

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('books.status', BookStatus::Available->value);
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (blank($term)) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($term) {
            $q->where('books.title', 'like', "%{$term}%")
              ->orWhere('books.author', 'like', "%{$term}%");
        });
    }

    public function scopeInGenre(Builder $query, $genreId): Builder
    {
        if (blank($genreId)) {
            return $query;
        }

        return $query->whereHas('genres', fn ($q) => $q->where('genres.id', $genreId));
    }

    public function scopeInRegion(Builder $query, $regionId): Builder
    {
        if (blank($regionId)) {
            return $query;
        }

        return $query->whereHas('owner', fn ($q) => $q->where('users.region_id', $regionId));
    }

    /** Buku dari kota yang sama dengan pengguna tampil lebih dulu (T25). */
    public function scopeOrderByUserRegion(Builder $query, ?int $regionId): Builder
    {
        if (! $regionId) {
            return $query;
        }

        return $query->orderByRaw(
            '(select case when users.region_id = ? then 0 else 1 end from users where users.id = books.owner_id)',
            [$regionId]
        );
    }
}