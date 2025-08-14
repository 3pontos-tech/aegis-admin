<?php

declare(strict_types=1);

namespace App\Models;

use App\Policies\ExpensePolicy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

#[UsePolicy(ExpensePolicy::class)]
final class Expense extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = [
        'amount',
        'date',
        'description',
        'receipt_path',
        'company_id',
        'user_id',
        'report_id',
        'category_id',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('receipt')
            ->useDisk('public');
    }

    protected function casts(): array
    {
        return [
            'date' => 'datetime',
            'receipt_path' => 'array',
        ];
    }
}
