<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Expenses\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

final class ExpenseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('amount')
                    ->required()
                    ->numeric(),
                DateTimePicker::make('date')
                    ->required(),
                TextInput::make('description')
                    ->required(),
                TextInput::make('receipt_path')
                    ->required(),
                TextInput::make('status')
                    ->required(),
                Select::make('company_id')
                    ->relationship('company', 'name')
                    ->required(),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Select::make('report_id')
                    ->relationship('report', 'title')
                    ->required(),
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required(),
            ]);
    }
}
