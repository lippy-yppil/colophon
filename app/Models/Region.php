<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Region extends Model
{
    protected $fillable = ['name', 'province'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function books(): HasManyThrough
    {
        return $this->hasManyThrough(Book::class, User::class, 'region_id', 'owner_id');
    }
}