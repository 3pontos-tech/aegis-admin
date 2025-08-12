<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Reports\Schemas;

use App\Enums\ReportStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

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
                                        'submitted' => ReportStatus::Submitted->value,
                                    ])
                                    ->enum(ReportStatus::class)
                                    ->required(),
                            ]),
                        Tab::make('Expenses')
                            ->schema([
                                Repeater::make('expenses')
                                    ->relationship('expenses')
                                    ->schema([
                                        TextInput::make('amount')
                                            ->label('Amount')
                                            ->numeric()
                                            ->required(),

                                        DateTimePicker::make('date')
                                            ->label('Date')
                                            ->required(),

                                        TextInput::make('description')
                                            ->label('Description')
                                            ->required(),

                                        FileUpload::make('receipt_path')
                                            ->label('Receipt')
                                            ->multiple()
                                            ->image()
                                            ->required(),

                                        Select::make('company_id')
                                            ->relationship('company', 'name')
                                            ->preload()
                                            ->required(),

                                        Select::make('user_id')
                                            ->label('User')
                                            ->relationship('user', 'name', function (Builder $query): void {
                                                $query->where('id', auth()->id());
                                            })
                                            ->required(),

                                        Select::make('category_id')
                                            ->label('Category')
                                            ->relationship('category', 'name')
                                            ->required(),
                                    ])
                                    ->defaultItems(1),
                            ]),
                    ]),
            ]);
    }
}
