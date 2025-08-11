<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Reimbursements\Pages;

use App\Filament\Admin\Resources\Reimbursements\ReimbursementResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateReimbursement extends CreateRecord
{
    protected static string $resource = ReimbursementResource::class;
}
