<?php

declare(strict_types=1);

use Illuminate\Support\Str;
use App\Filament\Admin\Resources\Companies\Pages\CreateCompany;
use App\Models\Company;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Livewire\livewire;

it('automatically generate a slug from the title', function (): void {
    actingAs(User::factory()->create());

    $name = 'name without slug';
    livewire(CreateCompany::class)
        ->assertOk()
        ->fillForm([
            'name' => $name,
        ])
        ->assertSchemaStateSet([
            'slug' => Str::slug($name),
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    assertDatabaseCount(Company::class, 2);
    assertDatabaseHas(Company::class, [
        'name' => $name,
        'slug' => Str::slug($name),
    ]);
});
