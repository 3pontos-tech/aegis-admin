<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Expenses\Schemas;

use App\Enums\ReportStatus;
use App\Filament\Shared\Schemas\Form\CompanyDependentSelect;
use App\Models\Category;
use App\Models\Report;
use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

final class ExpenseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
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

                Select::make('user_id')
                    ->relationship('user', 'name'),

                Select::make('category_id')
                    ->label('Category')
                    ->options(fn() => Category::query()->pluck('name', 'id'))
                    ->preload()
                    ->required(),

            ]);
    }
}
