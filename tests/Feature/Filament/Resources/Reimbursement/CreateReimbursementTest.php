<?php

declare(strict_types=1);

use App\Enums\ReimbursementStatus;
use App\Enums\ReportStatus;
use App\Filament\Admin\Resources\Reimbursements\Pages\CreateReimbursement;
use App\Models\Company;
use App\Models\Expense;
use App\Models\Reimbursement;
use App\Models\Report;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->company = Company::factory()->create();
    $this->user->company()->associate($this->company);
    $this->report = Report::factory()->has(Expense::factory())->approved()->create();
    $this->report->user()->associate($this->user);
    $this->expense = Expense::query()->first();
    $this->expense->company()->associate($this->company);
    $this->expense->user()->associate($this->user);
    $this->report->company()->associate($this->company);
    $this->report->save();
    actingAs($this->user);
});

test('reimbursement status should be Created by default', function (): void {

    livewire(CreateReimbursement::class)
        ->assertOk()
        ->fillForm([
            'company_id' => $this->company->getKey(),
            'report_id' => $this->report->id,
            'amount' => (int) $this->expense->amount,
            'payment_method' => 'PayPal',
            'reference' => 'whatssup',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $reimbursement = Reimbursement::query()->first();
    expect($reimbursement->status->value)
        ->toBe(ReimbursementStatus::Created->value);

});

it('should load only approved reports at reimbursement form', function (): void {
    livewire(CreateReimbursement::class)
        ->assertOk()
        ->fillForm(['company_id' => $this->company->id])
        ->assertSchemaStateSet([
            'report_id' => $this->report->name,
        ]);

    $this->report->update(['status' => ReportStatus::Rejected]);
    livewire(CreateReimbursement::class)
        ->assertOk()
        ->fillForm(['company_id' => $this->company->id])
        ->assertSchemaStateSet([
            'report_id' => null,
        ]);
});

it('loads amount after report has been chosen', function (): void {
    $this->expense->update(['amount' => 500]);
    livewire(CreateReimbursement::class)
        ->assertOk()
        ->fillForm([
            'company_id' => $this->company->id,
            'report_id' => $this->report->id,
        ])
        ->assertSchemaStateSet([
            'amount' => $this->expense->amount,
        ]);
});

it('load only reports that does not have an reimbursement associated', function (): void {
    $reimbursement = Reimbursement::factory()->create();
    $reimbursement->report()->associate($this->report);
    $anotherReport = Report::factory()->create();

    livewire(CreateReimbursement::class)
        ->assertOk()
        ->fillForm([
            'company_id' => $this->company->id,
        ])
        ->assertSchemaStateSet([
            'report_id' => null,
        ]);

    $reimbursement->report()->associate($anotherReport);
    livewire(CreateReimbursement::class)
        ->assertOk()
        ->fillForm([
            'company_id' => $this->company->id,
        ])
        ->assertSchemaStateSet([
            'report_id' => $this->report->name,
        ]);
});

test('only reports that belongs to the company can be loaded', function (): void {
    $report = Report::factory()->create();
    livewire(CreateReimbursement::class)
        ->assertOk()
        ->fillForm([
            'company_id' => $report->company->getKey(),
        ])
        ->assertSchemaStateSet([
            'report_id' => $report->name,
        ]);

    livewire(CreateReimbursement::class)
        ->assertOk()
        ->fillForm([
            'company_id' => $this->company->id,
        ])
        ->assertSchemaStateSet(function (array $state) use ($report): void {
            expect($state['report_id'])
                ->not->toBe($report->id);
        });

    expect($report->company->getKey())->not->toBe($this->company->id);
});
