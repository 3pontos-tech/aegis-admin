<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Approvals\Pages;

use App\Filament\Admin\Resources\Approvals\ApprovalResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

final class ListApprovals extends ListRecords
{
    protected static string $resource = ApprovalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
