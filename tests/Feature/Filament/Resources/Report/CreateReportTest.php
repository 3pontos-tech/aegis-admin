<?php

declare(strict_types=1);

use App\Enums\ReportStatus;
use App\Filament\Admin\Resources\Reports\Pages\CreateReport;
use App\Models\Company;
use App\Models\Report;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Livewire\livewire;

it('should create a report', function (): void {
    $user = User::factory()->createOne();
    $company = Company::factory()->createOne();
    $user->company()->associate($company);

    actingAs($user);
    livewire(CreateReport::class)
        ->fillForm([
            'title' => 'report title',
            'description' => 'report description',
            'status' => ReportStatus::Draft,
        ])
        ->call('create')
        ->assertHasNoErrors();

    assertDatabaseHas(Report::class, [
        'title' => 'report title',
        'description' => 'report description',
        'status' => ReportStatus::Draft,
        'company_id' => $company->getKey(),
        'user_id' => $user->getKey(),
    ]);
});
