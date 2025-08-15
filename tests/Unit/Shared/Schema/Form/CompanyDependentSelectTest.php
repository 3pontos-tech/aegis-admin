<?php

declare(strict_types=1);

use App\Filament\Shared\Schemas\Form\CompanyDependentSelect;
use Filament\Forms\Components\Select;

it('should be instance of Select', function (): void {
    $companyDependentSelect = CompanyDependentSelect::make('name');

    expect($companyDependentSelect)->toBeInstanceOf(Select::class);
});
it('should be required', function (): void {
    $companyDependentSelect = CompanyDependentSelect::make('name');

    expect($companyDependentSelect->isRequired())->toBeTrue();
});
it('should be preloaded', function (): void {
    $companyDependentSelect = CompanyDependentSelect::make('name');

    expect($companyDependentSelect->isPreloaded())->toBeTrue();
});

it('should be searchable', function (): void {
    $companyDependentSelect = CompanyDependentSelect::make('name');

    expect($companyDependentSelect->isSearchable())->toBeTrue();
});

