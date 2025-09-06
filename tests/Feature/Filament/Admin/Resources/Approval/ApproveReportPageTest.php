<?php

declare(strict_types=1);

use App\Enums\ApprovalStatus;
use App\Enums\ReportStatus;
use App\Filament\Admin\Resources\Reports\Pages\ApproveReport;
use App\Models\Approval;
use App\Models\Company;
use App\Models\Expense;
use App\Models\Report;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Livewire\livewire;
use function PHPUnit\Framework\assertStringContainsString;

beforeEach(function (): void {
    $this->company = Company::factory()->create();
    $this->userReporter = User::factory()
        ->has(Report::factory())
        ->createOne();
    $this->userReporter->company()->associate($this->company);
    $this->userApprover = User::factory()->createOne();
    $this->userApprover->company()->associate($this->company);
    $this->report = Report::factory()->for($this->userReporter)->submitted()->create();
    $this->company->reports()->save($this->report);
    Expense::factory()->count(2)->for($this->report)->create();

    actingAs($this->userApprover);
});

it('loads the information at the ApproveReportPage', function (): void {

    $component = livewire(ApproveReport::class, ['record' => $this->report->getKey()])
        ->assertOk();

    $this->report->expenses()->each(function (Expense $expense) use ($component): void {
        $component->assertSee($expense->date->format('d/m/y'));
        $component->assertSee($expense->amount);
        $component->assertSee($expense->description);
    });
});

test('should be able to approve or reject a report', function ($value): void {
    livewire(ApproveReport::class, ['record' => $this->report->getKey()])
        ->assertOk()
        ->set([
            'data' => [
                'status' => $value,
                'comments' => 'we cant pay for this',
            ],
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    assertDatabaseHas(Approval::class, [
        'status' => $value,
        'comments' => 'we cant pay for this',
        'company_id' => $this->report->company->getKey(),
        'report_id' => $this->report->getKey(),
        'approver_id' => $this->userApprover->getKey(),
    ]);
})->with([
    ApprovalStatus::Rejected,
    ApprovalStatus::Approved,
]);

it('should notify user that approval status was changed', function ($value): void {
    livewire(ApproveReport::class, ['record' => $this->report->getKey()])
        ->assertOk()
        ->set([
            'data' => [
                'status' => $value,
                'comments' => 'we cant pay for this',
            ],
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $notification = $this->userReporter->notifications()->latest()->first();
    assertStringContainsString(
        sprintf('Your report %s status was ', $this->report->id).$value->value,
        $notification->data['title']
    );
})->with([
    ApprovalStatus::Rejected,
    ApprovalStatus::Approved,
]);

test('should update report status after creating the approval', function ($approvalStatus, $reportStatus): void {

    livewire(ApproveReport::class, ['record' => $this->report->getKey()])
        ->assertOk()
        ->set([
            'data' => [
                'status' => $approvalStatus,
                'comments' => 'we cant pay for this',
            ],
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $this->report->refresh();

    expect($this->report->status)->toBe($reportStatus);

})->with([
    [ApprovalStatus::Approved, ReportStatus::Approved],
    [ApprovalStatus::Rejected, ReportStatus::Rejected],
]);
