<?php

namespace App\Services;

use App\Enums\BookStatus;
use App\Enums\LoanStatus;
use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LoanService
{
    /** T27: ajukan pinjam. Aturan bisnis dicek di StoreLoanRequest. */
    public function request(Book $book, User $borrower, array $data): Loan
    {
        return DB::transaction(fn () => Loan::create([
            'book_id' => $book->id,
            'borrower_id' => $borrower->id,
            'status' => LoanStatus::Requested,
            'requested_at' => now(),
            'due_date' => $data['due_date'] ?? null,
            'borrower_note' => $data['borrower_note'] ?? null,
        ]));
    }

    /** requested -> approved (pemilik). */
    public function approve(Loan $loan, ?string $ownerNote, ?string $dueDate = null): Loan
    {
        return DB::transaction(function () use ($loan, $ownerNote, $dueDate) {
            $loan = $this->lock($loan);
            $this->ensureStatus($loan, [LoanStatus::Requested], 'disetujui');

            $hasActive = Loan::where('book_id', $loan->book_id)
                ->where('id', '!=', $loan->id)
                ->active()
                ->exists();

            if ($hasActive) {
                throw ValidationException::withMessages([
                    'loan' => 'Buku ini sudah punya pinjaman aktif lain.',
                ]);
            }

            $loan->update([
                'status' => LoanStatus::Approved,
                'approved_at' => now(),
                'owner_note' => $ownerNote,
                'due_date' => $dueDate ?? $loan->due_date,
            ]);

            return $loan;
        });
    }

    /** requested -> rejected (pemilik). */
    public function reject(Loan $loan, ?string $ownerNote): Loan
    {
        return DB::transaction(function () use ($loan, $ownerNote) {
            $loan = $this->lock($loan);
            $this->ensureStatus($loan, [LoanStatus::Requested], 'ditolak');

            $loan->update([
                'status' => LoanStatus::Rejected,
                'owner_note' => $ownerNote,
            ]);

            return $loan;
        });
    }

    /** requested/approved -> cancelled (peminjam). */
    public function cancel(Loan $loan): Loan
    {
        return DB::transaction(function () use ($loan) {
            $loan = $this->lock($loan);
            $this->ensureStatus($loan, [LoanStatus::Requested, LoanStatus::Approved], 'dibatalkan');

            $loan->update(['status' => LoanStatus::Cancelled]);

            return $loan;
        });
    }

    /** approved -> borrowed (pemilik); books.status ikut borrowed. */
    public function handover(Loan $loan, ?string $ownerNote = null): Loan
    {
        return DB::transaction(function () use ($loan, $ownerNote) {
            $loan = $this->lock($loan);
            $this->ensureStatus($loan, [LoanStatus::Approved], 'ditandai diserahkan');

            $loan->update([
                'status' => LoanStatus::Borrowed,
                'owner_note' => $ownerNote ?? $loan->owner_note,
            ]);
            $loan->book()->update(['status' => BookStatus::Borrowed]);

            return $loan;
        });
    }

    /** borrowed -> returned (pemilik); books.status kembali available. */
    public function markReturned(Loan $loan, ?string $ownerNote = null): Loan
    {
        return DB::transaction(function () use ($loan, $ownerNote) {
            $loan = $this->lock($loan);
            $this->ensureStatus($loan, [LoanStatus::Borrowed], 'ditandai dikembalikan');

            $loan->update([
                'status' => LoanStatus::Returned,
                'returned_at' => now(),
                'owner_note' => $ownerNote ?? $loan->owner_note,
            ]);
            $loan->book()->update(['status' => BookStatus::Available]);

            return $loan;
        });
    }

    private function lock(Loan $loan): Loan
    {
        return Loan::whereKey($loan->id)->lockForUpdate()->firstOrFail();
    }

    /** @param  array<int, LoanStatus>  $allowedFrom */
    private function ensureStatus(Loan $loan, array $allowedFrom, string $action): void
    {
        if (! in_array($loan->status, $allowedFrom, true)) {
            throw ValidationException::withMessages([
                'loan' => "Pinjaman berstatus {$loan->status->value} tidak bisa {$action}.",
            ]);
        }
    }
}