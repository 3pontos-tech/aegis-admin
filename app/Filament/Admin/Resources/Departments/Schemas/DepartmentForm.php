<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Departments\Schemas;

use App\Filament\Shared\Schemas\Form\NameInput;
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
                Select::make('manager_id')
                    ->relationship('manager', 'name')
                    ->required()
                    ->reactive(),
                Select::make('users')
                    ->relationship(name: 'users', titleAttribute: 'name', modifyQueryUsing: function (Builder $query, Get $get): Builder {
                        if ($managerId = $get('manager_id')) {
                            $query->where('users.id', '!=', $managerId);
                        }

                        return $query;
                    })
                    ->required()
                    ->multiple()
                    ->preload(),
            ]);
    }
}
