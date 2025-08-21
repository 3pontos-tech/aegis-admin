<?php

declare(strict_types=1);

namespace App\Observers;

use App\Enums\ReportStatus;
use App\Models\Report;

final class ReportObserver
{
    public function updated(Report $report): void
    {
        if ($report->isDirty('status') && $report->status === ReportStatus::Submitted) {
            $report->submitted_at = now();
            $report->saveQuietly();
        }
    }
}
