<?php

declare(strict_types=1);

namespace App\DTOs;

use App\Enums\ApprovalStatus;

final readonly class ApprovalDTO
{
    public function __construct(
        public int $companyId,
        public int $reportId,
        public int $approverId,
        public ApprovalStatus $status,
        public string $comments
    ) {}

    public static function make(
        int $companyId,
        int $reportId,
        int $approverId,
        ApprovalStatus $status,
        string $comments
    ): self {
        return new self(
            companyId: $companyId,
            reportId: $reportId,
            approverId: $approverId,
            status: $status,
            comments: $comments
        );
    }
}
