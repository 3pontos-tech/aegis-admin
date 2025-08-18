<?php

declare(strict_types=1);

namespace App\Filament\Shared\Pages;

use App\Models\Company;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

final class Dashboard extends BaseDashboard
{
    use HasFiltersForm;

    public function filtersForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->description('Filter')
                    ->schema([
                        Select::make('company_id')
                            ->options(Company::query()->pluck('name', 'id'))
                            ->label('Company')
                            ->searchable(),
                        DatePicker::make('startDate')
                            ->beforeOrEqual(today()->toString())
                            ->live(),
                        DatePicker::make('endDate'),
                    ])
                    ->collapsed()
                    ->columnSpanFull(),
            ]);
    }
}
