<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ReimbursementStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

final class Reimbursement extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'report_id',
        'amount',
        'status',
        'payment_method',
        'payment_date',
        'reference',
    ];

    protected $casts = [
        'status' => ReimbursementStatus::class,
        'payment_date' => 'datetime',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    public function user(): HasOneThrough
    {
        return $this->hasOneThrough(
            User::class,
            Report::class,
            'user_id',
            'id',
            'id',
            'user_id'
        );
    }
}
