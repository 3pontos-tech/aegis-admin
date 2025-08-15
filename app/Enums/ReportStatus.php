<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ReportStatus: string implements HasColor, HasLabel
{
    case Draft = 'draft';
    case Submitted = 'submitted';
    case Approved = 'approved';
    case Rejected = 'rejected';
    case Reimbursed = 'reimbursed';

    public function getColor(): array
    {
        return match ($this) {
            self::Draft => Color::Gray,
            self::Submitted => Color::Blue,
            self::Approved => Color::Emerald,
            self::Rejected => Color::Red,
            self::Reimbursed => Color::Purple,
        };
    }

    public function getLabel(): string
    {

        return match ($this) {
            self::Draft => 'Draft',
            self::Submitted => 'Submitted',
            self::Approved => 'Approved',
            self::Rejected => 'Rejected',
            self::Reimbursed => 'Reimbursed',
        };
    }
}
