<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Expenses\Schemas;

use App\Enums\ReportStatus;
use App\Filament\Admin\Resources\Reports\RelationManagers\ExpensesRelationManager;
use App\Filament\Shared\Schemas\Form\CompanyDependentSelect;
use App\Models\Category;
use App\Models\Company;
use App\Models\Report;
use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Livewire\Component;

final class ExpenseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Select::make('company_id')
                    ->label('Company')
                    ->options(fn () => Company::query()->pluck('name', 'id'))
                    ->preload()
                    ->required()
                    ->default(fn (Component $livewire) => $livewire instanceof ExpensesRelationManager
                        ? $livewire->ownerRecord->company_id
                        : null
                    )
                    ->hiddenOn(ExpensesRelationManager::class),
                // when the component is the ExpensesRelationManager company_id is set by the record itself

                CompanyDependentSelect::make('user_id', User::class)
                    ->label('User'),

                CompanyDependentSelect::make('report_id', Report::class, 'title', 'status', ReportStatus::Submitted)
                    ->label('Report')
                    ->hiddenOn(ExpensesRelationManager::class),

                CompanyDependentSelect::make('category_id', Category::class)
                    ->label('Category'),

                TextInput::make('amount')
                    ->required()
                    ->prefix('R$')
                    ->numeric(),
                DateTimePicker::make('date')
                    ->native(false)
                    ->required(),
                TextInput::make('description')
                    ->required()
                    ->maxLength(255),

                SpatieMediaLibraryFileUpload::make('receipt')
                    ->collection('receipt')
                    ->label('Receipt Path')
                    ->multiple()
                    ->image()
                    ->required(),
            ]);
    }
}
