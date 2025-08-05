<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Reports\Pages;

use App\Filament\Admin\Resources\Reports\ReportResource;
use App\Models\Company;
use Filament\Resources\Pages\CreateRecord;

final class CreateReport extends CreateRecord
{
    protected static string $resource = ReportResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        $data['company_id'] = auth()->user()?->company_id ?? Company::query()->first()?->id;
        $data['status'] = 'draft';
        $data['submitted_at'] = now();

        return $data;
    }
}
