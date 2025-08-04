<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Departments\Pages;

use App\Filament\Admin\Resources\Departments\DepartmentResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateDepartment extends CreateRecord
{
    protected static string $resource = DepartmentResource::class;
}
