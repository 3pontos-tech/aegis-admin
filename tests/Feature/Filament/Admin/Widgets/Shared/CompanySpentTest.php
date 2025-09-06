<?php

declare(strict_types=1);

use App\Livewire\CompanySpent;
use App\Models\Company;
use App\Models\Report;
use App\Models\User;

it('should see the message when company does not have any report', function (): void {
    $company = Company::factory()->create();
    Livewire::actingAs(User::factory()->create())
        ->test(CompanySpent::class, ['pageFilters' => ['company_id' => $company->getKey()]])
        ->assertOk()
        ->assertSee('Select a Company Witch Has Reports');
});
it('should see the total spent of a company', function (): void {
    $company = Company::factory()->create();
    Report::factory()->recycle($company)->count(10)->create();
    $total = Report::query()->where('company_id', $company->getKey())->sum('total');

    Livewire::actingAs(User::factory()->create())
        ->test(CompanySpent::class, [
            'pageFilters' => [
                'company_id' => $company->getKey(),
                'startDate' => now()->subYear(),
                'endDate' => today(),
            ],
        ])
        ->assertOk()
        ->assertSee(sprintf('%s, Total Spent: R$ %s', $company->name, $total));
});
