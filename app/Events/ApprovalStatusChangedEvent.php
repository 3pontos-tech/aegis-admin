<?php

declare(strict_types=1);

namespace App\Events;

use App\Enums\ApprovalStatus;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class ApprovalStatusChangedEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;
    public function __construct(public ApprovalStatus $status, public int $reportId) {}
}
