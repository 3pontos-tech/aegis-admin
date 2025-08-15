<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Departments\Schemas;

use App\Filament\Shared\Schemas\Form\CompanyDependentSelect;
use App\Filament\Shared\Schemas\Form\NameInput;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

final class DepartmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                NameInput::make(),
                TextInput::make('budget')
                    ->required()
                    ->numeric(),
                Select::make('company_id')
                    ->relationship('company', 'name')
                    ->required(),

                CompanyDependentSelect::make('manager_id', User::class, 'name')
                    ->label('Manager')
                    ->required(),

                Select::make('users')
                    ->relationship(name: 'users', titleAttribute: 'name', modifyQueryUsing: function (Builder $query, Get $get): Builder {
                        if ($managerId = $get('manager_id')) {
                            $query->where('users.id', '!=', $managerId)
                                ->where('company_id', $get('company_id'));
                        }

                        return $query->where('company_id', $get('company_id'));
                    })
                    ->required(fn (Get $get): bool => is_null($get('manager_id')))
                    ->multiple()
                    ->preload(),
            ]);
    }
}
