<?php

declare(strict_types=1);

use App\Filament\Admin\Resources\Expenses\Pages\ListExpenses;
use App\Models\Company;
use App\Models\Expense;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    $this->company = Company::factory()->has(User::factory()->has(Expense::factory()->count(10)))->create();
    $this->user = $this->company->users()->first();
    actingAs($this->user);
});
describe('filter tests', function (): void {

    test('company filter', function (): void {
        livewire(ListExpenses::class)
            ->assertOk()
            ->assertTableFilterExists('company')
            ->filterTable('company', $this->company)
            ->assertCanSeeTableRecords($this->company->expenses()->get());
    });

    test('user filter', function (): void {
        $anotherExpenses = Expense::factory()->count(10)->create();
        $this->company->expenses()->saveMany($anotherExpenses);
        livewire(ListExpenses::class)
            ->assertOk()
            ->assertTableFilterExists('user')
            ->filterTable('user', $this->user)
            ->assertCanSeeTableRecords($this->user->expenses()->get())
            ->assertCanNotSeeTableRecords($anotherExpenses);
    });
});
