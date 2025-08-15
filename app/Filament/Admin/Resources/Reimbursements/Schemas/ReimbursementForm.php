<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Reimbursements\Schemas;

use App\Enums\ReportStatus;
use App\Models\Report;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

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
                                $query->where('company_id', $companyId)
                                    ->where('status', '=', ReportStatus::Approved);
                            }

                            $query->whereDoesntHave('reimbursement', function ($q): void {
                                $q->where('report_id', '=', DB::raw('reports.id'));
                            });
                        })
                    ->required()
                    ->reactive()
                    ->disabled(fn (Get $get): bool => ! $get('company_id'))
                    ->afterStateUpdated(function (Set $set, Get $get): void {
                        if ($reportId = $get('report_id')) {
                            $amount = Report::query()->find($reportId)->expenses()->sum('amount');
                            $set('amount', $amount);
                        }
                    }),
                TextInput::make('amount')
                    ->required()
                    ->readOnly()
                    ->numeric(),
                Select::make('status')
                    ->required()
                    ->hidden(),
                TextInput::make('payment_method')
                    ->required(),
                TextInput::make('payment_date')
                    ->required()
                    ->nullable()
                    ->hidden(),
                TextInput::make('reference')
                    ->required(),
            ]);
    }
}
