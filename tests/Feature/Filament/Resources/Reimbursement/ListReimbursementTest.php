<?php

declare(strict_types=1);

use App\Enums\ReimbursementStatus;
use App\Filament\Admin\Resources\Reimbursements\Pages\ListReimbursements;
use App\Models\Company;
use App\Models\Reimbursement;
use App\Models\Report;
use App\Models\User;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    $this->company = Company::factory()
        ->has(User::factory()->has(Report::factory(10)->approved()))
        ->create();
    $this->user = User::query()->first();
    $this->report = $this->user->reports->first();
    $this->reimbursement = Reimbursement::factory()
        ->for($this->report)
        ->for($this->company)
        ->approved()
        ->create();

    $this->reimbursements = Reimbursement::factory()->count(10)->create();

});
describe('filter tests', function (): void {
    test('status filter', function ($status): void {
        $this->reimbursements->each(fn ($reimbursement) => $reimbursement->update(['status' => $status]));

        livewire(ListReimbursements::class)
            ->assertOk()
            ->assertTableFilterExists('status')
            ->filterTable('status', $status)
            ->assertCanSeeTableRecords($this->reimbursements);

    })->with([
        ReimbursementStatus::cases(),
    ]);

    test('user filter', function (): void {
        livewire(ListReimbursements::class)
            ->assertOk()
            ->assertTableFilterExists('user')
            ->filterTable('user', $this->user)
            ->assertCanSeeTableRecords([$this->user->reimbursements->first()])
            ->assertCanNotSeeTableRecords($this->reimbursements);

    });

    test('company filter', function (): void {
        livewire(ListReimbursements::class)
            ->assertOk()
            ->assertTableFilterExists('company')
            ->filterTable('company', $this->company)
            ->assertCanSeeTableRecords([$this->user->reimbursements->first()])
            ->assertCanNotSeeTableRecords($this->reimbursements);
    });
});
