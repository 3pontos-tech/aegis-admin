<?php

declare(strict_types=1);

namespace App\Actions\Approval;

use App\DTOs\ApprovalDTO;
use App\Events\ApprovalStatusChangedEvent;
use App\Models\Approval;

final class CreateApprovalAction
{
    public function execute(
        ApprovalDTO $approval
    ): void {
        Approval::query()->create([
            'company_id' => $approval->companyId,
            'report_id' => $approval->reportId,
            'approver_id' => $approval->approverId,
            'status' => $approval->status,
            'level' => 'manager',
            'comments' => $approval->comments,
            'approved_at' => now(),
        ]);

        ApprovalStatusChangedEvent::dispatch($approval->status, $approval->reportId);
    }
}
