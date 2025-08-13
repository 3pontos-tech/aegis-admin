<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Reports\Tables;

use App\Enums\ReportStatus;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

final class ReportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('description')
                    ->searchable(),
                TextColumn::make('status')
                    ->searchable()
                    ->badge(),
                TextColumn::make('total')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('submitted_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('company.name')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(ReportStatus::class),
                SelectFilter::make('company')
                    ->preload()
                    ->searchable()
                    ->relationship('company', 'name'),
                SelectFilter::make('user')
                    ->preload()
                    ->searchable()
                    ->relationship('user', 'name'),

            ])->filtersFormColumns(3)
            ->filtersFormWidth(Width::FourExtraLarge)
            ->persistFiltersInSession()
            ->recordActions([
                EditAction::make(),
                Action::make('Approve')
                    ->url(fn ($record) => route('filament.admin.resources.reports.approve-report', $record)),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
