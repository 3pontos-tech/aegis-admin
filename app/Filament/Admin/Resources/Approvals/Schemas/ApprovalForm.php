<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Approvals\Schemas;

use App\Enums\ApprovalStatus;
use App\Enums\ReportStatus;
use App\Filament\Shared\Schemas\Form\CompanyDependentSelect;
use App\Models\Report;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

final class ApprovalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('company_id')
                    ->relationship('company', 'name')
                    ->required(),

                CompanyDependentSelect::make('report_id', Report::class, 'title', 'status', ReportStatus::Submitted->value)
                    ->label('Report')
                    ->required(),

                TextInput::make('level')
                    ->required(),
                Select::make('status')
                    ->options(ApprovalStatus::class)
                    ->enum(ApprovalStatus::class)
                    ->required(),
                TextInput::make('comments')
                    ->required()
                    ->minlength(3)
                    ->maxLength(255),
                DateTimePicker::make('approved_at')
                    ->hidden()
                    ->required(),
            ]);
    }
}
