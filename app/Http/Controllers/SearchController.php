<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Genre;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(Request $request): View
    {
        $request->validate([
            'q'         => ['nullable', 'string', 'max:100'],
            'genre_id'  => ['nullable', 'integer', 'exists:genres,id'],
            'region_id' => ['nullable', 'integer', 'exists:regions,id'],
        ]);

        $user = $request->user();

        $query = Book::available()
            ->with(['genres', 'owner.region'])
            ->search($request->q)
            ->inGenre($request->genre_id);

        if ($request->boolean('in_my_city') && $user->region_id) {
            $query->inRegion($user->region_id);
        } else {
            $query->inRegion($request->region_id)
                  ->orderByUserRegion($user->region_id);
        }

        $books = $query->latest('books.created_at')
            ->paginate(12)
            ->withQueryString();

        return view('search.index', [
            'books'   => $books,
            'genres'  => Genre::orderBy('name')->get(),
            'regions' => Region::orderBy('name')->get(),
        ]);
    }
}