<?php

declare(strict_types=1);

use App\Livewire\StatsOverview;
use App\Models\Company;
use App\Models\Reimbursement;
use App\Models\Report;
use App\Models\User;

it('should see the total of companies', function (): void {
    $companies = Company::factory()->count(10)->create();
    $totalCompanies = $companies->count();
    Livewire::actingAs(User::factory()->create())
        ->test(StatsOverview::class, ['pageFilters' => [
            'company_id' => $companies->first()->getKey(),
            'startDate' => now()->subYear(),
            'endDate' => today(),
        ]])
        ->assertOk()
        ->assertSeeInOrder(['Companies', $totalCompanies])
        ->assertSee('Number of companies');
});

it('should see the total spent by the company ', function (): void {
    $companies = Company::factory()->count(10)->create();
    Report::factory()->count(10)->recycle($companies->first())->create();
    $total = Report::query()->where('company_id', $companies->first()->getKey())->sum('total');

    Livewire::actingAs(User::factory()->create())
        ->test(StatsOverview::class, ['pageFilters' => [
            'company_id' => $companies->first()->getKey(),
            'startDate' => now()->subYear(),
            'endDate' => today()->addDay(),
        ]])
        ->assertOk()
        ->assertSeeInOrder(['R$: ', $total])
        ->assertSee('Sum of Companies Spent');
});

it('should see the total of reports', function (): void {
    $company = Company::factory()->create();
    $reports = Report::factory()->count(17)->recycle($company)->submitted()->create();
    Livewire::actingAs(User::factory()->create())
        ->test(StatsOverview::class, ['pageFilters' => [
            'company_id' => $company->getKey(),
            'startDate' => now()->subYear(),
            'endDate' => today(),
        ]])
        ->assertOk()
        ->assertSee('Total Reports')
        ->assertSeeTextInOrder(['Total Reports', $reports->count()])
        ->assertSee('Number of Reports Made');
});
it('should see the total of pending Reimbursements', function (): void {
    $company = Company::factory()->create();
    $reports = Report::factory()->count(17)->recycle($company)->submitted()->create();
    Reimbursement::factory()->count(10)->created()->create();

    Livewire::actingAs(User::factory()->create())
        ->test(StatsOverview::class, ['pageFilters' => [
            'company_id' => $company->getKey(),
            'startDate' => now()->subYear(),
            'endDate' => today(),
        ]])
        ->assertOk()

        ->assertSee('Total Reports')
        ->assertSeeTextInOrder(['Total Reports', $reports->count()])
        ->assertSee('Number of Reports Made');
});
