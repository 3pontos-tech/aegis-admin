<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Reports\Schemas;

use App\Enums\ReportStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

final class ReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                TextInput::make('description')
                    ->required(),
                Select::make('status')
                    ->options(fn(string $operation): array => $operation === 'create'
                        ? [
                            'draft' => ReportStatus::Draft->value,
                        ]
                        : [
                            'draft' => ReportStatus::Draft->value,
                            'submitted' => ReportStatus::Submitted->value,
                        ])
                    ->required(),
            ]);
    }
}
