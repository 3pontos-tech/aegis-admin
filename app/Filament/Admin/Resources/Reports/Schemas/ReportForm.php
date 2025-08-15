<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Reports\Schemas;

use App\Enums\ReportStatus;
use App\Models\Category;
use App\Models\Report;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

final class ReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make()
                    ->tabs([
                        Tab::make('Report-Information')
                            ->schema([
                                TextInput::make('title')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('description')
                                    ->required()
                                    ->maxLength(255),

                                Select::make('company_id')
                                    ->label('Company')
                                    ->preload()
                                    ->relationship('company', 'name')
                                    ->required(),

                                Select::make('status')
                                    ->hidden(fn (string $operation): bool => $operation !== 'edit')
                                    ->options([
                                        'draft' => ReportStatus::Draft->value,
                                        'submitted' => ReportStatus::Submitted->value,
                                    ])
                                    ->default(ReportStatus::Draft->value)
                                    ->enum(ReportStatus::class)
                                    ->required(),
                                TextInput::make('total')
                                    ->hidden()
                                    ->reactive()
                                    ->required(),
                            ]),
                    ]),
            ]);
    }

    public static function configureExpenseAction(): array
    {
        return [
            TextInput::make('amount')
                ->label('Amount')
                ->prefix('R$')
                ->minValue(1)
                ->required(),

            DateTimePicker::make('date')
                ->label('Date')
                ->native(false)
                ->required(),

            TextInput::make('description')
                ->label('Description')
                ->required(),

            SpatieMediaLibraryFileUpload::make('receipt')
                ->collection('receipt')
                ->label('Receipt Path')
                ->multiple()

                ->image()
                ->required(),

            TextInput::make('company_id')
                ->required()
                ->hidden(),

            TextInput::make('user_id')
                ->label('User')
                ->hidden()
                ->required(),

            Select::make('category_id')
                ->label('Category')
                ->options(fn(Report $record) => Category::query()->where('company_id', $record->company_id)->pluck('name', 'id')->toArray())
                ->preload()
                ->searchable()
                ->required(),
        ];

    }
}
