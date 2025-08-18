<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Reports\RelationManagers;

use App\Filament\Admin\Resources\Expenses\ExpenseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\Width;
use Filament\Tables\Table;

final class ExpensesRelationManager extends RelationManager
{
    protected static string $relationship = 'expenses';

    protected static ?string $relatedResource = ExpenseResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make()
                    ->mutateDataUsing(function (array $data) {
                        $data['report_id'] = $this->ownerRecord->id;
                        $data['company_id'] = $this->ownerRecord->company_id;

                        return $data;
                    })
                    ->after($this->updateReportTotal())
                    ->modalWidth(Width::Medium)
                    ->slideOver()
                    ->modal(),
            ]);
    }

    private function updateReportTotal(): void
    {
        $this->ownerRecord->update([
            'total' => $this->ownerRecord->expenses()->sum('amount'),
        ]);
    }
}
