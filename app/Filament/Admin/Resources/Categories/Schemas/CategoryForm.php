<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Categories\Schemas;

use App\Filament\Shared\Schemas\Form\NameInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

final class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                NameInput::make(),
                TextInput::make('description')
                    ->required(),
                Select::make('company_id')
                    ->relationship('company', 'name')
                    ->required(),
            ]);
    }
}
