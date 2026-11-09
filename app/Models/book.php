public function scopeSearch($query, $term)
{
    if ($term) {
        $query->where(function ($q) use ($term) {
            $q->where('title', 'LIKE', "%{$term}%")
              ->orWhere('author', 'LIKE', "%{$term}%");
        });
    }
}

public function scopeInGenre($query, $genreId)
{
    if ($genreId) {
        $query->whereHas('genres', function ($q) use ($genreId) {
            $q->where('genres.id', $genreId);
        });
    }
}

public function scopeOrderByUserRegion($query, $userRegionId)
{
    if ($userRegionId) {
        $query->join('users', 'books.owner_id', '=', 'users.id')
              ->orderByRaw("CASE WHEN users.region_id = ? THEN 0 ELSE 1 END", [$userRegionId])
              ->select('books.*');
    }
}