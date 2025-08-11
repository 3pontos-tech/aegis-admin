<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Reimbursements\Tables;

use App\Filament\Shared\Schemas\Table\Columns\TimestampsColumns;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
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
                ...TimestampsColumns::make(),
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
