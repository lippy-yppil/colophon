<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        $myBooksCount = $user->books()->count();

        $incomingLoans = $user->incomingLoans()
            ->whereIn('loans.status', ['requested', 'approved', 'borrowed'])
            ->with(['book', 'borrower.region'])
            ->latest('loans.requested_at')
            ->get();

        $myLoans = $user->loans()
            ->with(['book.owner.region'])
            ->latest('requested_at')
            ->get();

        return view('dashboard', compact('myBooksCount', 'incomingLoans', 'myLoans'));
    }
}