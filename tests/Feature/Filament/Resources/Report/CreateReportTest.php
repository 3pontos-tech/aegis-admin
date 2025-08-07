<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Storage;
use App\Enums\ReportStatus;
use App\Filament\Admin\Resources\Reports\Pages\CreateReport;
use App\Models\Category;
use App\Models\Company;
use App\Models\Report;
use App\Models\User;
use Filament\Forms\Components\Repeater;
use Illuminate\Http\UploadedFile;

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
    Repeater::fake();
    Storage::fake('public');
    $image = UploadedFile::fake()->image('image.jpg');
    $category = Category::factory()->for($this->company)->createOne();
    livewire(CreateReport::class)
        ->fillForm([
            'title' => 'report title',
            'description' => 'report description',
            'status' => ReportStatus::Draft,
            'expenses' => [
                [
                    'amount' => 150,
                    'date' => now()->format('Y-m-d H:i:s'),
                    'description' => 'Almoço com cliente',
                    'receipt_path' => $image,
                    'company_id' => $this->company->getKey(),
                    'user_id' => $this->user->getKey(),
                    'category_id' => $category->getKey(),
                ],
            ],
        ])
        ->call('create')
        ->assertHasNoErrors();

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

    test('status::validations', function ($value, $rule): void {

        livewire(CreateReport::class)
            ->fillForm([
                'status' => $value,
            ])
            ->call('create')
            ->assertHasFormErrors(['status' => $rule]);
    })->with([
        'required' => ['', 'The status field is required.'],
        'enum' => ['not-valid', 'The selected status is invalid.'],
    ]);
});
