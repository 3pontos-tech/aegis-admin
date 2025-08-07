<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ApprovalStatus: string implements HasColor, HasLabel
{
    case Approved = 'Approved';
    case Rejected = 'Rejected';

    public function getColor(): array
    {
        return match ($this) {
            self::Approved => Color::Green,
            self::Rejected => Color::Red,
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::Approved => 'Approved',
            self::Rejected => 'Rejected',
        };
    }
}
