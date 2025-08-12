<?php

declare(strict_types=1);

namespace App\Filament\Shared\Schemas\Form;

use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Utilities\Get;

final class CompanyDependentSelect extends Select
{
    protected string $name;

    public static function make(
        ?string $name = null,
        string $relatedModel = '',
        string $displayColumn = 'name',
        mixed $condition = null,
        mixed $conditionValue = null,
    ): static {
        $select = parent::make($name);

        $select->options(function (Get $get) use ($relatedModel, $displayColumn, $condition, $conditionValue) {
            $companyId = $get('company_id');
            if (! $companyId) {
                return [];
            }

            if (! $condition) {
                $relatedModel::where('company_id', $companyId)->pluck($displayColumn, 'id');
            }

            return $relatedModel::where('company_id', $companyId)->where($condition, $conditionValue)->pluck($displayColumn, 'id');
        });

        $select->required();
        $select->preload();
        $select->searchable();
        $select->reactive();

        return $select;
    }
}
