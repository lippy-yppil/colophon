<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLoanRequest;
use App\Models\Book;
use App\Models\Loan;
use App\Services\LoanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class LoanController extends Controller
{
    public function __construct(private LoanService $loans)
    {
    }

    public function show(Loan $loan): View
    {
        Gate::authorize('view', $loan);

        $loan->load(['book.owner.region', 'borrower.region']);

        return view('loans.show', ['loan' => $loan]);
    }

    public function store(StoreLoanRequest $request, Book $book): RedirectResponse
    {
        $this->loans->request($book, $request->user(), $request->validated());

        return redirect()->route('dashboard')
            ->with('status', 'Permintaan pinjam terkirim. Tunggu persetujuan pemilik.');
    }

    public function cancel(Loan $loan): RedirectResponse
    {
        Gate::authorize('cancel', $loan);
        $this->loans->cancel($loan);

        return back()->with('status', 'Permintaan pinjam dibatalkan.');
    }

    public function approve(Request $request, Loan $loan): RedirectResponse
    {
        Gate::authorize('approve', $loan);
        $data = $request->validate([
            'owner_note' => ['nullable', 'string', 'max:500'],
            'due_date' => ['nullable', 'date', 'after:today'],
        ]);
        $this->loans->approve($loan, $data['owner_note'] ?? null, $data['due_date'] ?? null);

        return back()->with('status', 'Permintaan disetujui.');
    }

    public function reject(Request $request, Loan $loan): RedirectResponse
    {
        Gate::authorize('reject', $loan);
        $data = $request->validate(['owner_note' => ['nullable', 'string', 'max:500']]);
        $this->loans->reject($loan, $data['owner_note'] ?? null);

        return back()->with('status', 'Permintaan ditolak.');
    }

    public function handover(Request $request, Loan $loan): RedirectResponse
    {
        Gate::authorize('handover', $loan);
        $data = $request->validate(['owner_note' => ['nullable', 'string', 'max:500']]);
        $this->loans->handover($loan, $data['owner_note'] ?? null);

        return back()->with('status', 'Buku ditandai sudah diserahkan.');
    }

    public function return(Request $request, Loan $loan): RedirectResponse
    {
        Gate::authorize('return', $loan);
        $data = $request->validate(['owner_note' => ['nullable', 'string', 'max:500']]);
        $this->loans->markReturned($loan, $data['owner_note'] ?? null);

        return back()->with('status', 'Buku ditandai sudah dikembalikan.');
    }
}