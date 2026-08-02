<?php

namespace App\Enums;

enum VerificationStatus: string
{
    case Unverified = 'unverified';
    case NeedsVerification = 'needs_verification';
    case Verified = 'verified';
}
