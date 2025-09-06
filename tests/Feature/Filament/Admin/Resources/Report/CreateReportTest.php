<?php

declare(strict_types=1);

use App\Enums\ReportStatus;
use App\Filament\Admin\Resources\Reports\Pages\CreateReport;
use App\Models\Company;
use App\Models\Report;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    $this->user = User::factory()->createOne();
    $this->company = Company::factory()->createOne();
    $this->user->company()->associate($this->company);
    actingAs($this->user);
});

it('should create a report', function (): void {
    livewire(CreateReport::class)
        ->fillForm([
            'company_id' => $this->company->getKey(),
            'title' => 'report title',
            'user_id' => $this->user->getKey(),
            'description' => 'report description',
            'status' => ReportStatus::Draft,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    assertDatabaseHas(Report::class, [
        'title' => 'report title',
        'description' => 'report description',
        'status' => ReportStatus::Draft,
        'company_id' => $this->company->getKey(),
        'user_id' => $this->user->getKey(),

    ]);
});

describe('validation::tests', function (): void {

    test('title::validations', function ($value, $rule): void {

        livewire(CreateReport::class)
            ->fillForm([
                'title' => $value,
            ])
            ->call('create')
            ->assertHasFormErrors(['title' => $rule]);
    })->with([
        'required' => ['', 'The title field is required.'],
        'max:255' => [str_repeat('a', 256), 'The title field must not be greater than 255 characters.'],
    ]);

    test('description::validations', function ($value, $rule): void {

        livewire(CreateReport::class)
            ->fillForm([
                'description' => $value,
            ])
            ->call('create')
            ->assertHasFormErrors(['description' => $rule]);
    })->with([
        'required' => ['', 'The description field is required.'],
        'max:255' => [str_repeat('a', 256), 'The description field must not be greater than 255 characters.'],
    ]);
});
