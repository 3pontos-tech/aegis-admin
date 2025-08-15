<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Reimbursements\Pages;

use App\Filament\Admin\Resources\Reimbursements\ReimbursementResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

final class EditReimbursement extends EditRecord
{
    protected static string $resource = ReimbursementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
