<?php

declare(strict_types=1);

use App\Enums\ApprovalStatus;
use App\Enums\ReportStatus;
use App\Filament\Admin\Resources\Approvals\Pages\CreateApproval;
use App\Models\Approval;
use App\Models\Company;
use App\Models\Report;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    $this->company = Company::factory()->create();
    $this->userReporter = User::factory()
        ->has(Report::factory()->submitted())
        ->createOne();

    $this->userReporter->company()->associate($this->company);
    $this->userApprover = User::factory()->createOne();
    $this->userApprover->company()->associate($this->company);
    $this->company->reports()->save($this->userReporter->reports()->first());

    actingAs($this->userApprover);
});

it('should be able to approve a Report', function (): void {
    livewire(CreateApproval::class)
        ->fillForm([
            'company_id' => $this->company->getKey(),
            'report_id' => $this->userReporter->reports->first()->getKey(),
            'level' => 'level-3',
            'status' => ApprovalStatus::Approved,
            'comments' => 'ok homie',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    assertDatabaseCount(Approval::class, 1);
    assertDatabaseHas(Approval::class, [
        'company_id' => $this->company->getKey(),
        'report_id' => $this->userReporter->reports->first()->getKey(),
        'level' => 'level-3',
        'approver_id' => $this->userApprover->getKey(),
        'status' => ApprovalStatus::Approved,
        'comments' => 'ok homie',
    ]);
});

test('only submitted report can be loaded ', function ($status): void {
    $report = $this->userReporter->reports()->first();
    $report->update(['status' => $status]);
    livewire(CreateApproval::class)
        ->fillForm([
            'company_id' => $this->company->getKey(),
            'report_id' => $report->getKey(),
            'level' => 'level-3',
            'status' => ApprovalStatus::Approved,
            'comments' => 'ok homie',
        ])
        ->call('create')
        ->assertHasFormErrors(['report_id']);
})->with([
    ReportStatus::Approved,
    ReportStatus::Rejected,
    ReportStatus::Draft,
    ReportStatus::Reimbursed,
]);
