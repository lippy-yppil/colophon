<?php

namespace App\Http\Controllers;

use App\Enums\BookStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShelfController extends Controller
{
    // T20: satu rak per pemilik; rak dari kota yang sama tampil lebih dulu
    public function index(Request $request): View
    {
        $regionId = $request->user()->region_id;

        $owners = User::query()
            ->whereHas('books', fn ($q) => $q->available())
            ->with([
                'region',
                'books' => fn ($q) => $q->available()->with('genres')->latest('books.created_at'),
            ])
            ->when($regionId, fn ($q) => $q->orderByRaw('case when region_id = ? then 0 else 1 end', [$regionId]))
            ->orderBy('name')
            ->paginate(4);

        return view('shelf.index', compact('owners'));
    }

    // T21: rak milik satu pengguna
    public function userShelf(User $user): View
    {
        $user->load('region');

        $books = $user->books()
            ->where('status', '!=', BookStatus::Hidden->value)
            ->with('genres')
            ->latest()
            ->get();

        return view('shelf.user', compact('user', 'books'));
    }
}