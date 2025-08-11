<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Companies\Schemas;

use App\Filament\Shared\Schemas\Form\NameInput;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

final class CompanyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                NameInput::make()
                    ->reactive()
                    ->afterStateUpdated(fn (Set $set, Get $get): mixed => $set('slug', Str::slug($get('name')))),
                TextInput::make('slug')
                    ->required()
                    ->readOnly()
                    ->unique(),
            ]);
    }
}
