<?php

declare(strict_types=1);

use App\Filament\Admin\Resources\Departments\Pages\CreateDepartment;
use App\Models\Company;
use App\Models\Department;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

it('should be able to create a department', function (): void {
    $employees = User::factory()->count(10)->create();
    $company = Company::factory()->create();
    $manager = User::factory()->create();

    actingAs($manager);

    livewire(CreateDepartment::class)
        ->fillForm([
            'budget' => 5000,
            'name' => 'Department name',
            'slug' => 'department-name',
            'manager_id' => $manager->id,
            'company_id' => $company->id,
            'users' => $employees->pluck('id')->toArray(),
        ])
        ->call('create')
        ->assertHasNoErrors();

    $department = Department::query()->first();
    expect($department->manager->getKey())
        ->toBe($manager->getKey());
});

test('department manager should be added to users relationship after creating a department', function (): void {
    $employees = User::factory()->count(10)->create();
    $company = Company::factory()->create();
    $manager = User::factory()->create();

    actingAs($manager);

    livewire(CreateDepartment::class)
        ->fillForm([
            'budget' => 5000,
            'name' => 'Department name',
            'slug' => 'department-name',
            'manager_id' => $manager->id,
            'company_id' => $company->id,
            'users' => $employees->pluck('id')->toArray(),
        ])
        ->call('create')
        ->assertHasNoErrors();

    $department = Department::query()->first();
    expect($department->manager->getKey())
        ->toBe($manager->getKey())
        ->and($department->users()->count())
        ->toBe($employees->count() + 1)
        ->and($department->users()->where('users.id', $manager->id)->exists())->toBeTrue();
});
