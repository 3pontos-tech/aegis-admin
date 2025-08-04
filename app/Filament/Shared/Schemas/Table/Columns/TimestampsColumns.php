<?php

declare(strict_types=1);

namespace App\Filament\Shared\Schemas\Table\Columns;

use Exception;
use Filament\Tables\Columns\TextColumn;

final class TimestampsColumns
{
    /**
     * @return TextColumn[]
     *
     * @throws Exception
     */
    public static function make(): array
    {
        return [
            TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }
}
