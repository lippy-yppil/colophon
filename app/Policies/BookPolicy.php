<?php

namespace App\Policies;

use App\Models\Book;
use App\Models\User;

class BookPolicy
{
    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Book $book): bool
    {
        return (int) $book->owner_id === (int) $user->id;
    }

    public function delete(User $user, Book $book): bool
    {
        return (int) $book->owner_id === (int) $user->id;
    }
}
