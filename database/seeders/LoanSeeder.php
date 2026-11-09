<?php

namespace Database\Seeders;

use App\Enums\BookStatus;
use App\Enums\LoanStatus;
use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Database\Seeder;

class LoanSeeder extends Seeder
{
    public function run(): void
    {
        $demo = User::where('email', 'demo@marginalia.test')->first() ?? User::first();
        $others = User::where('id', '!=', $demo->id)->get()->values();

        if ($others->count() < 3) {
            $this->command?->warn('LoanSeeder butuh minimal 4 pengguna. Dilewati.');

            return;
        }

        $demoBooks = Book::where('owner_id', $demo->id)
            ->where('status', BookStatus::Available->value)->get()->values();
        $otherBooks = Book::where('owner_id', '!=', $demo->id)
            ->where('status', BookStatus::Available->value)->get()->shuffle()->values();

        // Demo sebagai PEMINJAM (aktif: approved + borrowed + overdue = 3)
        $this->make($otherBooks->shift(), $demo, LoanStatus::Requested, [
            'due_date' => now()->addDays(14),
            'borrower_note' => 'Boleh ketemu di akhir pekan?',
        ]);
        $this->make($otherBooks->shift(), $demo, LoanStatus::Approved, [
            'approved_at' => now()->subDay(),
            'due_date' => now()->addDays(10),
            'owner_note' => 'Siap, ketemu di kafe dekat kampus.',
        ]);
        $this->make($otherBooks->shift(), $demo, LoanStatus::Borrowed, [
            'approved_at' => now()->subDays(4),
            'due_date' => now()->addDays(5),
        ]);
        $this->make($otherBooks->shift(), $demo, LoanStatus::Borrowed, [ // OVERDUE
            'requested_at' => now()->subDays(20),
            'approved_at' => now()->subDays(18),
            'due_date' => now()->subDays(3),
            'owner_note' => 'Mohon dikembalikan secepatnya ya.',
        ]);
        $this->make($otherBooks->shift(), $demo, LoanStatus::Returned, [
            'requested_at' => now()->subDays(40),
            'approved_at' => now()->subDays(38),
            'returned_at' => now()->subDays(25),
            'due_date' => now()->subDays(26),
        ]);
        $this->make($otherBooks->shift(), $demo, LoanStatus::Rejected, [
            'owner_note' => 'Maaf, buku sedang dipakai sendiri.',
        ]);

        // Demo sebagai PEMILIK
        $this->make($demoBooks->shift(), $others[0], LoanStatus::Requested, [
            'due_date' => now()->addDays(12),
            'borrower_note' => 'Saya ingin baca untuk tugas kuliah.',
        ]);
        $this->make($demoBooks->shift(), $others[1], LoanStatus::Borrowed, [
            'approved_at' => now()->subDays(3),
            'due_date' => now()->addDays(7),
        ]);
        $this->make($demoBooks->shift(), $others[2], LoanStatus::Returned, [
            'requested_at' => now()->subDays(30),
            'approved_at' => now()->subDays(28),
            'returned_at' => now()->subDays(10),
            'due_date' => now()->subDays(12),
        ]);
        $this->make($demoBooks->shift(), $others[0], LoanStatus::Cancelled);
    }

    private function make(?Book $book, User $borrower, LoanStatus $status, array $extra = []): void
    {
        if (! $book) {
            return;
        }

        Loan::create(array_merge([
            'book_id' => $book->id,
            'borrower_id' => $borrower->id,
            'status' => $status,
            'requested_at' => now()->subDays(7),
        ], $extra));

        if ($status === LoanStatus::Borrowed) {
            $book->update(['status' => BookStatus::Borrowed]);
        }
    }
}