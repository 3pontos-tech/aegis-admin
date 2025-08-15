<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Expenses\Pages;

use App\Filament\Admin\Resources\Expenses\ExpenseResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateExpense extends CreateRecord
{
    protected static string $resource = ExpenseResource::class;
}
