<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Enums\ApprovalStatus;
use App\Enums\ReportStatus;
use App\Events\ApprovalStatusChangedEvent;
use App\Models\Report;

final class UpdateReportStatusListener
{
    public function handle(ApprovalStatusChangedEvent $event): void
    {
        $report = Report::query()->find($event->reportId);

        $event->status->value === ApprovalStatus::Approved->value
            ? $report->update(['status' => ReportStatus::Approved])
            : $report->update(['status' => ReportStatus::Rejected]);
    }
}
