<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Report;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

final class CompanySpent extends ChartWidget
{
    use InteractsWithPageFilters;

    private ?string $companyName = '';

    private ?string $total = '';

    public function getHeading(): string
    {
        if ($this->companyName === null || $this->companyName === '' || $this->companyName === '0') {
            return 'Select a Company Witch Has Reports';
        }

        return sprintf('%s, Total Spent: R$ %s', $this->companyName, $this->total);
    }

    protected function getData(): array
    {
        if (! Report::query()->where('company_id', $this->pageFilters['company_id'])->exists()) {
            return [];
        }

        $companyReports = Report::query()->where('company_id', $this->pageFilters['company_id']);
        $company = $companyReports->first();
        $this->companyName = $company->company->name;
        $this->total = $companyReports->sum('total');

        $data = Trend::query($companyReports)
            ->between(
                start: $this->pageFilters['startDate'] ? Carbon::parse($this->pageFilters['startDate']) : now()->startOfYear(),
                end: $this->pageFilters['endDate'] ? Carbon::parse($this->pageFilters['endDate']) : now()->endOfYear(),
            )
            ->perMonth()
            ->sum('total');

        return [
            'datasets' => [
                [
                    'label' => sprintf('Company %s Total: %s', $this->companyName, $companyReports->sum('total')),
                    'data' => $data->map(fn (TrendValue $value): mixed => $value->aggregate),
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#9BD0F5',
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value): string => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
