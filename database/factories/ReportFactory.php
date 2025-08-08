<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ReportStatus;
use App\Models\Company;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

final class ReportFactory extends Factory
{
    protected $model = Report::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->word(),
            'description' => $this->faker->text(),
            'status' => $this->faker->randomElement(ReportStatus::cases()),
            'submitted_at' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'company_id' => Company::factory(),
            'user_id' => User::factory(),
        ];
    }

    public function approved(): self
    {
        return $this->state(fn (array $attributes): array => [
            'status' => ReportStatus::Approved,
        ]);
    }

    public function rejected(): self
    {
        return $this->state(fn (array $attributes): array => [
            'status' => ReportStatus::Rejected,
        ]);
    }

    public function reimbursed(): self
    {
        return $this->state(fn (array $attributes): array => [
            'status' => ReportStatus::Reimbursed,
        ]);
    }

    public function draft(): self
    {
        return $this->state(fn (array $attributes): array => [
            'status' => ReportStatus::Draft,
        ]);
    }

    public function submitted(): self
    {
        return $this->state(fn (array $attributes): array => [
            'status' => ReportStatus::Submitted,
        ]);
    }
}
