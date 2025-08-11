<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Reimbursements\Pages;

use App\Enums\ReimbursementStatus;
use App\Filament\Admin\Resources\Reimbursements\ReimbursementResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateReimbursement extends CreateRecord
{
    protected static string $resource = ReimbursementResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['status'] = ReimbursementStatus::Created;

        return $data;
    }
}
