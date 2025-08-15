<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Reimbursements\Tables;

use App\Enums\ReimbursementStatus;
use App\Filament\Shared\Schemas\Table\Columns\TimestampsColumns;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

final class ReimbursementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('company.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('report.title')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('amount')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->searchable()
                    ->badge()
                    ->sortable(),
                TextColumn::make('payment_method')
                    ->searchable(),
                TextColumn::make('payment_date')
                    ->searchable(),
                TextColumn::make('reference')
                    ->searchable(),
                TextColumn::make('report.user.name')
                    ->label('Owner')
                    ->searchable(),
                ...TimestampsColumns::make(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(ReimbursementStatus::class),
                SelectFilter::make('company')
                    ->preload()
                    ->searchable()
                    ->relationship('company', 'name'),

                SelectFilter::make('user')
                    ->preload()
                    ->searchable()
                    ->relationship('report.user', 'name'),

            ])->filtersFormColumns(3)
            ->filtersFormWidth(Width::FourExtraLarge)
            ->persistFiltersInSession()
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
