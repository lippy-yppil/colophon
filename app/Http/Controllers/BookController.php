public function show(Book $book)
{
    if ($book->status === 'hidden' && $book->owner_id !== auth()->id()) {
        abort(403, 'Buku ini disembunyikan.');
    }

    $book->load(['owner.region', 'genres']);

    return view('books.show', compact('book'));
}