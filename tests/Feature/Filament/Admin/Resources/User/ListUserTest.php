<?php

declare(strict_types=1);

use App\Filament\Admin\Resources\Users\Pages\ListUsers;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    $this->users = User::factory()->count(10)->create();
    actingAs(User::factory()->create());
});

it('list users information', function (): void {
    livewire(ListUsers::class)
        ->assertOk()
        ->assertCanSeeTableRecords($this->users)
        ->assertCountTableRecords(11)
        ->assertCanRenderTableColumn('name')
        ->assertCanRenderTableColumn('id')
        ->assertCanRenderTableColumn('email')
        ->assertCanRenderTableColumn('email_verified_at');
});

test('should not see users password', function (): void {
    livewire(ListUsers::class)
        ->assertOk()
        ->assertCanNotSeeTableRecords($this->users->pluck('password')->toArray())
        ->assertTableColumnDoesNotExist('password');
});

test('can search by name', function (): void {
    livewire(ListUsers::class)
        ->assertOk()
        ->searchTable($this->users->first()->name)
        ->assertCanSeeTableRecords($this->users->where('name', '=', $this->users->first()->name))
        ->assertCanNotSeeTableRecords($this->users->where('name', '!=', $this->users->first()->name));
});

test('can search by email', function (): void {
    livewire(ListUsers::class)
        ->assertOk()
        ->searchTable($this->users->first()->email)
        ->assertCanSeeTableRecords($this->users->where('email', '=', $this->users->first()->email))
        ->assertCanNotSeeTableRecords($this->users->where('email', '!=', $this->users->first()->email));
});

test('can sort users by email_verified_at', function (): void {
    livewire(ListUsers::class)
        ->assertOk()
        ->sortTable('email_verified_at')
        ->assertCanSeeTableRecords($this->users->sortBy('email_verified_at'), inOrder: true)
        ->sortTable('email_verified_at', 'desc')
        ->assertCanSeeTableRecords($this->users->sortByDesc('email_verified_at'), inOrder: true);
});
