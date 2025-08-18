<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\ReimbursementStatus;
use App\Enums\ReportStatus;
use App\Models\Company;
use App\Models\Reimbursement;
use App\Models\Report;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;

final class StatsOverview extends StatsOverviewWidget
{
    use InteractsWithPageFilters;

    protected int|array|null $columns = 2;

    protected function getHeading(): ?string
    {
        return 'Analytics';
    }

    protected function getStats(): array
    {
        $totalSpent = $this->totalSpent();

        $totalReports = $this->totalReports();

        $pendingReimbursements = $this->pendingReimbursements();

        return [
            Stat::make('Companies', Company::query()->count())
                ->icon(Heroicon::BuildingOffice)
                ->description('Number of companies')
                ->descriptionColor('success'),

            Stat::make('Platform Spent', 'R$: ' . $totalSpent)
                ->icon(Heroicon::Link)
                ->description('Sum of Companies Spent')
                ->descriptionColor('success'),

            Stat::make('Total Reports', $totalReports)
                ->icon(Heroicon::Envelope)
                ->description('Number of Reports Made')
                ->color('success'),

            Stat::make('Pending Reimbursements', $pendingReimbursements)
                ->icon(Heroicon::Ticket)
                ->description('Number of Pending Reimbursements')
                ->color('success'),
        ];
    }

    private function totalSpent(): mixed
    {
        $companyId = $this->pageFilters['company_id'];
        $startDate = $this->pageFilters['startDate'];
        $endDate = $this->pageFilters['endDate'];

        if (! $startDate && ! $endDate) {
            return $companyId
                ? Report::query()->where('company_id', $companyId)->sum('total')
                : Report::query()->sum('total');
        }

        if (! $startDate && $endDate) {
            return $companyId
                ? Report::query()
                    ->where('company_id', $companyId)
                    ->where('created_at', '<=', $endDate)
                    ->sum('total')
                : Report::query()->sum('total');
        }

        if ($startDate && ! $endDate) {
            return $companyId
                ? Report::query()
                    ->where('company_id', $companyId)
                    ->where('created_at', '>=', $startDate)
                    ->sum('total')
                : Report::query()
                    ->where('created_at', '>=', $startDate)
                    ->sum('total');
        }

        return $companyId
            ? Report::query()
                ->where('company_id', $companyId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('total')
            : Report::query()
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('total');
    }

    private function totalReports(): Report|int
    {
        return Report::query()->when($this->pageFilters['company_id'], fn (Builder $query) => $query->where('company_id', $this->pageFilters['company_id'])
            ->where('status', '!=', ReportStatus::Draft)->count(), fn (Builder $query) => $query->where('status', '!=', ReportStatus::Draft)->count());
    }

    private function pendingReimbursements(): Reimbursement|int
    {
        return Reimbursement::query()->when($this->pageFilters['company_id'],
            fn (Builder $query) => $query->where('company_id', $this->pageFilters['company_id'])
                ->where('status', ReimbursementStatus::Created)->count(),

            fn (Builder $query) => $query->where('status', ReimbursementStatus::Created)->count());
    }
}
