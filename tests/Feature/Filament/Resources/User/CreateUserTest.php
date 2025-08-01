<?php

declare(strict_types=1);

use App\Filament\Admin\Resources\Users\Pages\CreateUser;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\withoutExceptionHandling;
use function Pest\Livewire\livewire;

it('should be able to create a user', function (): void {
    withoutExceptionHandling();
    actingAs(User::factory()->create());

    livewire(CreateUser::class)
        ->fillForm([
            'name' => 'joe-doe',
            'email' => 'joe@doe.com',
            'email_verified_at' => now(),
            'password' => 'password',
            'confirm_password' => 'password',
        ])
        ->call('create')
        ->assertHasNoErrors()
        ->assertSuccessful();

    assertDatabaseCount(User::class, 2);
    assertDatabaseHas(User::class, [
        'name' => 'joe-doe',
        'email' => 'joe@doe.com',
    ]);

});
