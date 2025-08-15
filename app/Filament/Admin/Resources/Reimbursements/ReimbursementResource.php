<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Reimbursements;

use App\Filament\Admin\Resources\Reimbursements\Pages\CreateReimbursement;
use App\Filament\Admin\Resources\Reimbursements\Pages\EditReimbursement;
use App\Filament\Admin\Resources\Reimbursements\Pages\ListReimbursements;
use App\Filament\Admin\Resources\Reimbursements\Schemas\ReimbursementForm;
use App\Filament\Admin\Resources\Reimbursements\Tables\ReimbursementsTable;
use App\Models\Reimbursement;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

final class ReimbursementResource extends Resource
{
    protected static ?string $model = Reimbursement::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Ticket;

    protected static string|UnitEnum|null $navigationGroup = 'Financial';

    public static function form(Schema $schema): Schema
    {
        return ReimbursementForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ReimbursementsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListReimbursements::route('/'),
            'create' => CreateReimbursement::route('/create'),
            'edit' => EditReimbursement::route('/{record}/edit'),
        ];
    }
}
