<?php

declare(strict_types=1);

use App\Enums\ApprovalStatus;
use App\Filament\Admin\Resources\Approvals\Pages\ListApprovals;
use App\Models\Approval;
use App\Models\Company;
use App\Models\Report;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

describe('filters test', function (): void {

    beforeEach(function (): void {
        $this->company = Company::factory()->has(User::factory()->has(Report::factory()->submitted()))->create();
        $this->approvals = Approval::factory()->count(10)->create();
        $this->approvals->each(fn ($approval) => $approval->company()->associate($this->company)->save());

        $this->user = $this->company->users()->first();
        actingAs($this->user);
    });

    test('status filter', function ($status): void {
        $this->approvals->each(fn (Approval $approval) => $approval->update(['status' => $status]));

        livewire(ListApprovals::class)
            ->assertOk()
            ->assertTableFilterExists('status')
            ->filterTable('status', $status)
            ->assertCanSeeTableRecords($this->approvals);

    })->with([
        ApprovalStatus::cases(),
    ]);

    test('company filter', function (): void {
        $anotherApprovals = Approval::factory()->count(10)->create();
        livewire(ListApprovals::class)
            ->assertOk()
            ->assertTableFilterExists('company')
            ->filterTable('company', $this->company)
            ->assertCanSeeTableRecords($this->approvals)
            ->assertCanNotSeeTableRecords($anotherApprovals);
    });

    test('approver filter', function (): void {
        $anotherApprovals = Approval::factory()->count(10)->for(User::factory(), 'approver')->create();
        $approver = $anotherApprovals->first()->approver;

        livewire(ListApprovals::class)
            ->assertOk()
            ->assertTableFilterExists('approver')
            ->filterTable('approver', $approver)
            ->assertCanSeeTableRecords($anotherApprovals)
            ->assertCanNotSeeTableRecords($this->approvals);
    });
});
