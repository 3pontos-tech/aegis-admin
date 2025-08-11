<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Reimbursements\Schemas;

use App\Enums\ReimbursementStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

final class ReimbursementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('company_id')
                    ->relationship('company', 'name')
                    ->required()
                    ->reactive(),
                Select::make('report_id')
                    ->relationship('report', 'title',
                        function (Builder $query, Get $get): void {
                            if ($companyId = $get('company_id')) {
                                $query->where('company_id', $companyId);
                            }
                        })
                    ->required()
                    ->reactive()
                    ->disabled(fn (Get $get): bool => ! $get('company_id')),
                TextInput::make('amount')
                    ->required()
                    ->readOnly()
                    ->numeric(),
                Select::make('status')
                    ->required()
                    ->hidden()
                    ->default(ReimbursementStatus::Created),
                TextInput::make('payment_method')
                    ->required(),
                TextInput::make('payment_date')
                    ->required()
                    ->default(now())
                    ->hidden(),
                TextInput::make('reference')
                    ->required(),
            ]);
    }
}
