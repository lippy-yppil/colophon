<?php

namespace App\Http\Controllers;

use App\Enums\BookStatus;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
use App\Models\Genre;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class BookController extends Controller
{
    public function index(Request $request): View
    {
        $books = $request->user()->books()
            ->with('genres')
            ->latest()
            ->paginate(10);

        return view('books.index', compact('books'));
    }

    public function create(): View
    {
        return view('books.create', [
            'book'   => new Book(),
            'genres' => Genre::orderBy('name')->get(),
        ]);
    }

    public function store(StoreBookRequest $request): RedirectResponse
    {
        $data = $request->safe()->except(['genres', 'cover']);

        $book = $request->user()->books()->create($data);
        $book->genres()->sync($request->validated('genres'));

        return redirect()->route('books.index')
            ->with('status', 'Buku berhasil ditambahkan ke rakmu.');
    }

    public function edit(Book $book): View
    {
        Gate::authorize('update', $book);

        return view('books.edit', [
            'book'   => $book->load('genres'),
            'genres' => Genre::orderBy('name')->get(),
        ]);
    }

    public function update(UpdateBookRequest $request, Book $book): RedirectResponse
    {
        $data = $request->safe()->except(['genres', 'cover']);

        if ($book->status === BookStatus::Borrowed) {
            unset($data['status']);
        }

        $book->update($data);
        $book->genres()->sync($request->validated('genres'));

        return redirect()->route('books.index')
            ->with('status', 'Buku berhasil diperbarui.');
    }

    public function destroy(Book $book): RedirectResponse
    {
        Gate::authorize('delete', $book);

        if ($book->loans()->exists()) {
            return back()->with('error', 'Buku ini punya riwayat peminjaman, jadi tidak bisa dihapus. Sembunyikan saja dari rak.');
        }

        $book->delete();

        return redirect()->route('books.index')
            ->with('status', 'Buku dihapus dari rakmu.');
    }
}