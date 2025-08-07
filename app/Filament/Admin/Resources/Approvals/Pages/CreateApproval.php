<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Approvals\Pages;

use App\Filament\Admin\Resources\Approvals\ApprovalResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateApproval extends CreateRecord
{
    protected static string $resource = ApprovalResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['approver_id'] = auth()->id();
        $data['approved_at'] = now();

        return $data;
    }
}
