<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ReimbursementStatus;
use App\Models\Company;
use App\Models\Reimbursement;
use App\Models\Report;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

final class ReimbursementFactory extends Factory
{
    protected $model = Reimbursement::class;

    public function definition(): array
    {
        return [
            'amount' => $this->faker->randomFloat(),
            'status' => $this->faker->randomElement(ReimbursementStatus::cases()),
            'payment_method' => $this->faker->word(),
            'payment_date' => Carbon::now(),
            'reference' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'company_id' => Company::factory(),
            'report_id' => Report::factory(),
        ];
    }

    public function created(): self
    {
        return $this->state(fn (array $attributes): array => [
            'status' => ReimbursementStatus::Created,
        ]);
    }

    public function approved(): self
    {
        return $this->state(fn (array $attributes): array => [
            'status' => ReimbursementStatus::Approved,
        ]);
    }

    public function rejected(): self
    {
        return $this->state(fn (array $attributes): array => [
            'status' => ReimbursementStatus::Rejected,
        ]);
    }

    public function refunded(): self
    {
        return $this->state(fn (array $attributes): array => [
            'status' => ReimbursementStatus::Refunded,
        ]);
    }
}
