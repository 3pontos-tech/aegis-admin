<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ApprovalStatus;
use App\Models\Approval;
use App\Models\Company;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

final class ApprovalFactory extends Factory
{
    protected $model = Approval::class;

    public function definition(): array
    {
        return [
            'level' => $this->faker->word(),
            'status' => $this->faker->randomElement([ApprovalStatus::cases()]),
            'comments' => $this->faker->word(),
            'approved_at' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'company_id' => Company::factory(),
            'report_id' => Report::factory(),
            'approver_id' => User::factory(),
        ];
    }
}
