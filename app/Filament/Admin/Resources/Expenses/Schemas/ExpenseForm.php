<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Expenses\Schemas;

use App\Enums\ReportStatus;
use App\Filament\Shared\Schemas\Form\CompanyDependentSelect;
use App\Models\Category;
use App\Models\Report;
use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
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
                    ->required()
                    ->maxLength(255),
                FileUpload::make('receipt_path')
                    ->label('Receipt Image')
                    ->image()
                    ->multiple()
                    ->required(),
                Select::make('company_id')
                    ->relationship('company', 'name')
                    ->required(),
                CompanyDependentSelect::make('user_id', User::class, 'name')
                    ->label('User')
                    ->required(),
                CompanyDependentSelect::make('report_id', Report::class, 'title', 'status', ReportStatus::Submitted->value)
                    ->label('Report')
                    ->required(),

                CompanyDependentSelect::make('category_id', Category::class, 'name')
                    ->label('Category')
                    ->required(),
            ]);
    }
}
