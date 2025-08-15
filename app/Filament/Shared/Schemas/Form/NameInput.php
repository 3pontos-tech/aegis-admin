<?php

declare(strict_types=1);

namespace App\Filament\Shared\Schemas\Form;

use Filament\Forms\Components\TextInput;

final class NameInput extends TextInput
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->required();
        $this->maxLength(255);
        $this->placeholder(__('Enter your name'));

    }

    public static function make(?string $name = 'name'): static
    {
        return parent::make($name);
    }
}
