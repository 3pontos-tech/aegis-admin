<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Expenses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

final class ExpensesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('amount')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('date')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('description')
                    ->searchable(),
                TextColumn::make('receipt_path')
                    ->searchable(),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('company.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('report.title')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('category.name')
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
                //
            ])
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
