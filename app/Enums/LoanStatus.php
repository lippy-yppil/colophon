<?php

namespace App\Enums;

enum LoanStatus: string
{
    case Requested = 'requested';
    case Approved  = 'approved';
    case Rejected  = 'rejected';
    case Cancelled = 'cancelled';
    case Borrowed  = 'borrowed';
    case Returned  = 'returned';
}