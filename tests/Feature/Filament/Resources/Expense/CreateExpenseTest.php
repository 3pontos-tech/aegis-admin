<?php

declare(strict_types=1);

use App\Filament\Admin\Resources\Expenses\Pages\CreateExpense;
use App\Models\Category;
use App\Models\Company;
use App\Models\Report;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    $this->user = User::factory()->createOne();
    $this->company = Company::factory()->createOne();
    $this->user->company()->associate($this->company);
    $this->category = Category::factory()->for($this->company)->createOne();
    $this->report = Report::factory()
        ->for($this->company)
        ->for($this->user)
        ->createOne();

    actingAs($this->user);
});

it('should be able create to create an expense', function (): void {

    livewire(CreateExpense::class)
        ->assertOk()
        ->fillForm([
            'amount' => 10000,
            'date' => now(),
            'description' => 'description for expense',
            'receipt_path' => 'arrumar.pdf',
            'company_id' => $this->company->getKey(),
            'category_id' => $this->category->getKey(),
            'report_id' => $this->report->getKey(),
            'user_id' => $this->user->getKey(),
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect($this->user->expenses()->count())->toBeOne(1);
});

describe('validation::tests', function (): void {

    test('description::validation', function ($value, $rule): void {
        livewire(CreateExpense::class)
            ->assertOk()
            ->fillForm([
                'description' => $value,
            ])
            ->call('create')
            ->assertHasFormErrors(['description' => $rule]);
    })->with([
        'required' => ['', 'The description field is required.'],
        'max:255' => [str_repeat('a', 256), 'The description field must not be greater than 255 characters.'],
    ]);

    test('amount::validation', function ($value, $rule): void {

        livewire(CreateExpense::class)
            ->assertOk()
            ->fillForm([
                'amount' => $value,
            ])
            ->call('create')
            ->assertHasFormErrors(['amount' => $rule]);
    })->with([
        'required' => ['', 'The amount field is required.'],
        'numeric' => ['NaN', 'The amount field must be a number.'],
    ]);

    test('date::validation', function ($value, $rule): void {

        livewire(CreateExpense::class)
            ->assertOk()
            ->fillForm([
                'date' => $value,
            ])
            ->call('create')
            ->assertHasFormErrors(['date' => $rule]);
    })->with([
        'required' => ['', 'The date field is required.'],
    ]);

    test('date::validation', function ($value, $rule): void {

        livewire(CreateExpense::class)
            ->assertOk()
            ->fillForm([
                'date' => $value,
            ])
            ->call('create')
            ->assertHasFormErrors(['date' => $rule]);
    })->with([
        'required' => ['', 'The date field is required.'],
    ]);
});
