<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Reports\Widgets;

use App\Filament\Admin\Resources\Reports\Pages\ListReports;
use App\Models\Report;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Number;

final class ReportsOverview extends StatsOverviewWidget
{
    use InteractsWithPageTable;

    protected function getTablePage(): string
    {
        return ListReports::class;
    }

    protected function getStats(): array
    {
        return [
            $this->yearStats(),
            $this->todayStats(),
            $this->average(),
        ];
    }

    private function todayStats(): Stat
    {
        $startOfDay = now()->startOfDay();
        $endOfDay = now()->endOfDay();

        $today = Trend::query(Report::query()->whereBetween('submitted_at', [$startOfDay, $endOfDay]))
            ->between(
                start: $startOfDay,
                end: $endOfDay
            )
            ->perHour()
            ->count();

        return Stat::make('Today', $this->getPageTableQuery()->whereBetween('submitted_at', [$startOfDay, $endOfDay])->count())
            ->chart($today->map(fn (TrendValue $trendValue): mixed => $trendValue->aggregate)->toArray())
            ->color('success');
    }

    private function yearStats(): Stat
    {
        $onYear = Trend::model(Report::class)
            ->between(
                start: now()->subYear(),
                end: now()
            )
            ->perMonth()
            ->count();

        return Stat::make('Total', $this->getPageTableQuery()->count())
            ->chart($onYear->map(fn (TrendValue $trendValue): mixed => $trendValue->aggregate)->toArray())
            ->color('success');
    }

    private function average(): Stat
    {
        $format = Number::format((float) $this->getPageTableQuery()->avg('total'), maxPrecision: 2, locale: 'pt_BR');
        $format = 'R$: ' . $format;

        return Stat::make('Average', $format);
    }
}
