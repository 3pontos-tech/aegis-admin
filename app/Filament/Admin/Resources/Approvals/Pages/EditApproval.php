<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Approvals\Pages;

use App\Filament\Admin\Resources\Approvals\ApprovalResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

final class EditApproval extends EditRecord
{
    protected static string $resource = ApprovalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
