<?php

declare(strict_types=1);

use App\Enums\ApprovalStatus;
use App\Filament\Admin\Resources\Reports\Pages\ApproveReport;
use App\Models\Approval;
use App\Models\Company;
use App\Models\Expense;
use App\Models\Report;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Livewire\livewire;

it('loads the information at the ApproveReportPage', function (): void {
    $reporter = User::factory()->create();
    $approver = User::factory()->create();
    $report = Report::factory()->for($reporter)->create();
    Expense::factory()->count(2)->for($report)->create();

    actingAs($approver);

    $component = livewire(ApproveReport::class, ['record' => $report->getKey()])
        ->assertOk();

    $report->expenses()->each(function (Expense $expense) use ($component): void {
        $component->assertSee($expense->date->format('d/m/y'));
        $component->assertSee($expense->amount);
        $component->assertSee($expense->description);
        $component->assertSee($expense->receipt_path);
    });
});

test('should be able to approve or reject a report', function ($value): void {
    $company = Company::factory()->create();
    $userReporter = User::factory()
        ->has(Report::factory())
        ->createOne();
    $userReporter->company()->associate($company);
    $userApprover = User::factory()->createOne();
    $userApprover->company()->associate($company);
    $report = Report::factory()->for($userReporter)->create();
    $company->reports()->save($report);
    Expense::factory()->count(2)->for($report)->create();

    actingAs($userApprover);

    livewire(ApproveReport::class, ['record' => $report->getKey()])
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
        'company_id' => $report->company->getKey(),
        'report_id' => $report->getKey(),
        'approver_id' => $userApprover->getKey(),
    ]);
})->with([
    ApprovalStatus::Rejected,
    ApprovalStatus::Approved,
]);
