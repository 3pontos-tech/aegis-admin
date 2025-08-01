<?php

declare(strict_types=1);

use App\Filament\Admin\Resources\Users\Pages\ListUsers;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

it('list users information', function (): void {
    $users = User::factory()->count(10)->create();
    actingAs(User::factory()->create());

    livewire(ListUsers::class)
        ->assertOk()
        ->assertCanSeeTableRecords($users)
        ->assertCountTableRecords(11)
        ->assertCanRenderTableColumn('name')
        ->assertCanRenderTableColumn('id')
        ->assertCanRenderTableColumn('email')
        ->assertCanRenderTableColumn('email_verified_at');
});
