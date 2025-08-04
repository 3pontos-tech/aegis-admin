<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Companies\Schemas;

use App\Filament\Shared\Schemas\Form\NameInput;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

final class CompanyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                NameInput::make(),
                TextInput::make('slug')
                    ->required()
                    ->unique(),
            ]);
    }
}
