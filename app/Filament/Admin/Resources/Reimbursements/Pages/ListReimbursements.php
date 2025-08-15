<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Reimbursements\Pages;

use App\Filament\Admin\Resources\Reimbursements\ReimbursementResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

final class ListReimbursements extends ListRecords
{
    protected static string $resource = ReimbursementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
