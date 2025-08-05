<?php

declare(strict_types=1);

use App\Filament\Admin\Resources\Reports\Pages\ListReports;
use App\Models\Company;
use App\Models\Report;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    $this->user = User::factory()->createOne();
    $company = Company::factory()->createOne();
    $this->user->company()->associate($company);

    $this->reports = Report::factory()->count(5)
        ->for($this->user)
        ->for($company)
        ->create();

    actingAs($this->user);
});

it('should list all reports', function (): void {
    livewire(ListReports::class)
        ->assertOk()
        ->assertCanSeeTableRecords($this->reports)
        ->assertCountTableRecords($this->reports->count())
        ->assertCanRenderTableColumn('title')
        ->assertCanRenderTableColumn('description')
        ->assertCanRenderTableColumn('status')
        ->assertCanRenderTableColumn('submitted_at')
        ->assertCanRenderTableColumn('company.name')
        ->assertCanRenderTableColumn('user.name');
});
