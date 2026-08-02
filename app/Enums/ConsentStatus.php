<?php

namespace App\Enums;

enum ConsentStatus: string
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';
    case Expired = 'expired';
    case NotRequired = 'not_required';
}
