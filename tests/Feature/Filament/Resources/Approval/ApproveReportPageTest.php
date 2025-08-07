<?php

declare(strict_types=1);

use App\Filament\Admin\Resources\Reports\Pages\ApproveReport;
use App\Models\Expense;
use App\Models\Report;
use App\Models\User;

use function Pest\Laravel\actingAs;
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
