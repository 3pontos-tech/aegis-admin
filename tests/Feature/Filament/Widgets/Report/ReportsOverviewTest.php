<?php

declare(strict_types=1);

use App\Enums\ReportStatus;
use App\Filament\Admin\Resources\Reports\Widgets\ReportsOverview;
use App\Models\Report;
use App\Models\User;

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->reports = Report::factory()->count(10)->state(['total' => 100])->approved()->create();
});

it('should display the number of total reports', function (): void {
    $numberOfReports = Report::query()->count();

    Livewire::actingAs($this->user)
        ->test(ReportsOverview::class)
        ->assertOk()
        ->assertSee('Total')
        ->assertSee($numberOfReports);
});

it('should display the average of reports total', function (): void {
    $avg = Report::query()->avg('total');

    Livewire::actingAs($this->user)
        ->test(ReportsOverview::class)
        ->assertOk()
        ->assertSee('R$: ' . $avg);
});
it('should display the total of reports based on status', function (): void {
    // Default => All
    // 1 => Draft
    // 2 => Submitted
    // 3 => Approved
    // 4 => Rejected
    // 5 => Reimbursed

    Report::query()->take(5)->update([
        'status' => ReportStatus::Submitted,
        'total' => 150,
    ]);
    $avg = Report::whereStatus(ReportStatus::Submitted)->avg('total');

    Livewire::actingAs($this->user)
        ->withUrlParams(['activeTab' => 2])
        ->test(ReportsOverview::class)
        ->assertSeeInOrder(['Total', 5])
        ->assertSee('R$: ' . $avg);
});
