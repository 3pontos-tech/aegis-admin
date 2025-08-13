<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ReportStatus;
use App\Observers\ReportObserver;
use App\Policies\ReportPolicy;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[ObservedBy(ReportObserver::class)]
#[UsePolicy(ReportPolicy::class)]
final class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'submitted_at',
        'company_id',
        'user_id',
        'total',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    public function reimbursement(): HasOne
    {
        return $this->hasOne(Reimbursement::class);
    }

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'status' => ReportStatus::class,
        ];
    }
}
