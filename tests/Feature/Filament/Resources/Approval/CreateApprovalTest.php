<?php

declare(strict_types=1);

use App\Enums\ApprovalStatus;
use App\Filament\Admin\Resources\Approvals\Pages\CreateApproval;
use App\Models\Approval;
use App\Models\Company;
use App\Models\Report;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Livewire\livewire;

it('should be able to approve a Report', function (): void {
    $company = Company::factory()->create();
    $userReporter = User::factory()
        ->has(Report::factory())
        ->createOne();
    $userReporter->company()->associate($company);
    $userApprover = User::factory()->createOne();
    $userApprover->company()->associate($company);

    actingAs($userApprover);

    $approved_at = now();

    livewire(CreateApproval::class)
        ->fillForm([
            'company_id' => $company->getKey(),
            'report_id' => $userReporter->reports->first()->getKey(),
            'level' => 'level-3',
            'status' => ApprovalStatus::Approved,
            'comments' => 'ok homie',
            'approved_at' => $approved_at,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    assertDatabaseCount(Approval::class, 1);
    assertDatabaseHas(Approval::class, [
        'company_id' => $company->getKey(),
        'report_id' => $userReporter->reports->first()->getKey(),
        'level' => 'level-3',
        'approver_id' => $userApprover->getKey(),
        'status' => ApprovalStatus::Approved,
        'comments' => 'ok homie',
        'approved_at' => $approved_at,
    ]);

});
