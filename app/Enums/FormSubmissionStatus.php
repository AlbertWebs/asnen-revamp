<?php

namespace App\Enums;

enum FormSubmissionStatus: string
{
    case New = 'new';
    case InProgress = 'in_progress';
    case Resolved = 'resolved';
    case Spam = 'spam';
    case Archived = 'archived';
}
