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

describe('validation::tests', function (): void {

    beforeEach(function (): void {
        actingAs(User::factory()->create());
    });

    test('name::validations', function ($value, $rule): void {

        livewire(CreateUser::class)
            ->fillForm([
                'name' => $value,
            ])
            ->call('create')
            ->assertHasFormErrors(['name' => $rule]);

    })->with([
        'required' => ['', 'The name field is required.'],
        'max:255' => [str_repeat('a', 256), 'max:255'],
    ]);

    test('email::validations', function ($value, $rule): void {

        livewire(CreateUser::class)
            ->fillForm([
                'email' => $value,
            ])
            ->call('create')
            ->assertHasFormErrors(['email' => $rule]);

    })->with([
        'required' => ['', 'The email address field is required.'],
        'email' => ['not-email', 'The email address field must be a valid email address.'],
    ]);

    test('password::validations', function ($value, $rule): void {

        livewire(CreateUser::class)
            ->fillForm([
                'password' => $value,
            ])
            ->call('create')
            ->assertHasFormErrors(['password' => $rule]);

    })->with([
        'required' => ['', 'The password field is required.'],
    ]);
});
