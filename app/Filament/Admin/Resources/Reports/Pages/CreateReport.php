<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Reports\Pages;

use App\Enums\ReportStatus;
use App\Filament\Admin\Resources\Reports\ReportResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateReport extends CreateRecord
{
    protected static string $resource = ReportResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        $data['status'] = ReportStatus::Draft;

        $data['total'] = 0;

        return $data;
    }
}
