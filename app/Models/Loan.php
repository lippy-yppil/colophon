<?php

namespace App\Models;

use App\Enums\LoanStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Loan extends Model
{
    protected $fillable = [
        'book_id', 'borrower_id', 'status', 'requested_at',
        'approved_at', 'returned_at', 'due_date',
        'borrower_note', 'owner_note',
    ];

    protected function casts(): array
    {
        return [
            'status'       => LoanStatus::class,
            'due_date'     => 'date',
            'requested_at' => 'datetime',
            'approved_at'  => 'datetime',
            'returned_at'  => 'datetime',
        ];
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function borrower(): BelongsTo
    {
        return $this->belongsTo(User::class, 'borrower_id');
    }
}