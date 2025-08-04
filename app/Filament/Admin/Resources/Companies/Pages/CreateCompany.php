<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Companies\Pages;

use App\Filament\Admin\Resources\Companies\CompanyResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateCompany extends CreateRecord
{
    protected static string $resource = CompanyResource::class;
}
