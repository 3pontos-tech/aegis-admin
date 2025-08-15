<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ApprovalStatus;
use App\Policies\ApprovalPolicy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[UsePolicy(ApprovalPolicy::class)]
final class Approval extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'report_id',
        'approver_id',
        'level',
        'status',
        'comments',
        'approved_at',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    protected function casts(): array
    {
        return [
            'approved_at' => 'datetime',
            'status' => ApprovalStatus::class,
        ];
    }
}
