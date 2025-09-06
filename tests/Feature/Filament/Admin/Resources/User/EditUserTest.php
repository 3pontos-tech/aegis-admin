<?php

declare(strict_types=1);

use App\Filament\Admin\Resources\Users\Pages\EditUser;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Livewire\livewire;

it('should be able to edit user information', function (): void {
    $user = User::factory()->create([
        'name' => 'joedoe',
        'email' => 'joedoe@gmail.com',
    ]);

    actingAs($user);
    livewire(EditUser::class, ['record' => $user->getKey()])
        ->fillForm([
            'name' => 'joe-updated-doe',
            'email' => 'joe-updated-doe@gmail.com',
            'password' => $user->password,
        ])
        ->call('save')
        ->assertSuccessful()
        ->assertHasNoErrors();

    assertDatabaseCount(User::class, 1);
    assertDatabaseHas(User::class, [
        'name' => 'joe-updated-doe',
        'email' => 'joe-updated-doe@gmail.com',
    ]);
    assertDatabaseMissing(User::class, [
        'name' => 'joedoe',
        'email' => 'joedoe@gmail.com',
    ]);
});

test('edit form should load the informations', function (): void {
    $user = User::factory()->create();

    actingAs($user);
    livewire(EditUser::class, ['record' => $user->getKey()])
        ->assertOk()
        ->assertSchemaStateSet([
            'name' => $user->name,
            'email' => $user->email,
        ]);
});
