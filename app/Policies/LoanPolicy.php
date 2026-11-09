<?php

namespace App\Policies;

use App\Models\Loan;
use App\Models\User;

class LoanPolicy
{
    public function view(User $user, Loan $loan): bool
    {
        return $this->isOwner($user, $loan) || $this->isBorrower($user, $loan);
    }

    public function approve(User $user, Loan $loan): bool
    {
        return $this->isOwner($user, $loan);
    }

    public function reject(User $user, Loan $loan): bool
    {
        return $this->isOwner($user, $loan);
    }

    public function handover(User $user, Loan $loan): bool
    {
        return $this->isOwner($user, $loan);
    }

    public function return(User $user, Loan $loan): bool
    {
        return $this->isOwner($user, $loan);
    }

    public function cancel(User $user, Loan $loan): bool
    {
        return $this->isBorrower($user, $loan);
    }

    private function isOwner(User $user, Loan $loan): bool
    {
        return $loan->book->owner_id === $user->id;
    }

    private function isBorrower(User $user, Loan $loan): bool
    {
        return $loan->borrower_id === $user->id;
    }
}