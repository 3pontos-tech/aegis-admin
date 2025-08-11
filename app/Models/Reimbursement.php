<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ReimbursementStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'reference' => ReimbursementStatus::class,
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
}
