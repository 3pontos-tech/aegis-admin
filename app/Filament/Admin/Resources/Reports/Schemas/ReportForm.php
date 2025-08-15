<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Reports\Schemas;

use App\Enums\ReportStatus;
use App\Models\Category;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
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
                        Tab::make('Expenses')
                            ->schema([
                                Repeater::make('expenses')
                                    ->relationship('expenses')
                                    ->schema([
                                        TextInput::make('amount')
                                            ->label('Amount')
                                            ->numeric()
                                            ->minValue(1)
                                            ->required(),

                                        DateTimePicker::make('date')
                                            ->label('Date')
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
                                            ->options(function (Get $get) {
                                                if (! $companyId = $get('../../company_id')) {
                                                    return [];
                                                }

                                                return Category::query()->where('company_id', $companyId)->pluck('name', 'id')->toArray();
                                            })
                                            ->reactive()
                                            ->searchable()
                                            ->required(),
                                    ])
                                    ->defaultItems(1)
                                    ->mutateRelationshipDataBeforeCreateUsing(function (array $data, Get $get) {
                                        $data['company_id'] = $get('company_id');
                                        $data['user_id'] = auth()->id();

                                        return $data;
                                    }),
                            ]),
                    ]),
            ]);
    }
}
