<?php

declare(strict_types=1);

use App\Filament\Shared\Schemas\Form\NameInput;

beforeEach(function (): void {
    $this->nameInput = new NameInput('name');
    $reflection = new ReflectionClass(NameInput::class);
    $reflection = $reflection->getMethod('setUp');
    $reflection->setAccessible(true);
    $reflection->invoke($this->nameInput);
});

test('name input is required', function (): void {
    expect($this->nameInput->isRequired())->toBeTrue();
});
