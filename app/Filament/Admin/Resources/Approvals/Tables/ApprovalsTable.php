<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Approvals\Tables;

use App\Enums\ApprovalStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

final class ApprovalsTable
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
                TextColumn::make('approver.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('level')
                    ->searchable(),
                TextColumn::make('status')
                    ->searchable()
                    ->badge(),
                TextColumn::make('comments')
                    ->searchable(),
                TextColumn::make('approved_at')
                    ->dateTime()
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
                SelectFilter::make('company')
                    ->preload()
                    ->searchable()
                    ->relationship('company', 'name'),

                SelectFilter::make('approver')
                    ->label('Approver')
                    ->preload()
                    ->searchable()
                    ->relationship('approver', 'name'),

                SelectFilter::make('status')
                    ->preload()
                    ->searchable()
                    ->options(ApprovalStatus::class),

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
