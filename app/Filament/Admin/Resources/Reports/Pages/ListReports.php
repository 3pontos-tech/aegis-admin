<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Reports\Pages;

use App\Enums\ReportStatus;
use App\Filament\Admin\Resources\Reports\ReportResource;
use App\Filament\Admin\Resources\Reports\Widgets\ReportsOverview;
use App\Models\Report;
use Filament\Actions\CreateAction;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;

final class ListReports extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = ReportResource::class;

    public function getTabs(): array
    {
        return [
            Tab::make()
                ->label('All')
                ->badge(fn ($query) => Report::query()->count()),
            ...collect(ReportStatus::cases())->map(fn (ReportStatus $status): Tab => Tab::make()
                ->label($status->getLabel())
                ->badgeColor($status->getColor())
                ->modifyQueryUsing(fn ($query) => $query->where('status', $status))
                ->badge(fn ($query) => Report::query()->where('status', $status)->count()),
            )->toArray(),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ReportsOverview::class,
        ];
    }
}
