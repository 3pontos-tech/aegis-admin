<?php

declare(strict_types=1);

use App\Filament\Admin\Resources\Departments\Pages\CreateDepartment;
use App\Models\Company;
use App\Models\Department;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    $this->company = Company::factory()->has(User::factory()->count(10))->create();
    $this->employees = User::all();
    $this->manager = User::factory()->create();
    $this->company->users()->save($this->manager);

    actingAs($this->manager);
});

it('should be able to create a department', function (): void {

    livewire(CreateDepartment::class)
        ->fillForm([
            'budget' => 5000,
            'name' => 'Department name',
            'slug' => 'department-name',
            'manager_id' => $this->manager->id,
            'company_id' => $this->company->id,
            'users' => $this->employees->pluck('id')->toArray(),
        ])
        ->call('create')
        ->assertHasNoErrors();

    $department = Department::query()->first();
    expect($department->manager->getKey())
        ->toBe($this->manager->getKey());
});

test('department manager should be added to users relationship after creating a department', function (): void {
    livewire(CreateDepartment::class)
        ->fillForm([
            'budget' => 5000,
            'name' => 'Department name',
            'slug' => 'department-name',
            'manager_id' => $this->manager->id,
            'company_id' => $this->company->id,
            'users' => $this->employees->pluck('id')->toArray(),
        ])
        ->call('create')
        ->assertHasNoErrors();

    $department = Department::query()->first();
    expect($department->manager->getKey())
        ->toBe($this->manager->getKey())
        ->and($department->users()->count())
        ->toBe($this->employees->count() + 1)
        ->and($department->users()->where('users.id', $this->manager->id)->exists())->toBeTrue();
});
