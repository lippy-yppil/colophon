<?php

namespace App\Models;

use App\Enums\LoanStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Loan extends Model
{
    protected $fillable = [
        'book_id', 'borrower_id', 'status',
        'requested_at', 'approved_at', 'returned_at',
        'due_date', 'borrower_note', 'owner_note',
    ];

    protected function casts(): array
    {
        return [
            'status' => LoanStatus::class,
            'requested_at' => 'datetime',
            'approved_at' => 'datetime',
            'returned_at' => 'datetime',
            'due_date' => 'date',
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

    /** Loan aktif = approved atau borrowed (T28). */
    public function scopeActive(Builder $query): Builder
    {
        return $query->whereIn('status', [
            LoanStatus::Approved->value,
            LoanStatus::Borrowed->value,
        ]);
    }

    /** T32: overdue tidak disimpan, dihitung dari due_date. */
    public function scopeOverdue(Builder $query): Builder
    {
        return $query->where('status', LoanStatus::Borrowed->value)
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<', today());
    }

    public function isOverdue(): bool
    {
        return $this->status === LoanStatus::Borrowed
            && $this->due_date !== null
            && $this->due_date->lt(today());
    }
}