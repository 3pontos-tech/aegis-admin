<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Reports\Pages;

use App\Filament\Admin\Resources\Reports\ReportResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

final class EditReport extends EditRecord
{
    protected static string $resource = ReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
