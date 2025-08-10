<?php

declare(strict_types=1);

namespace Database\Factories;

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
            'status' => $this->faker->word(),
            'payment_method' => $this->faker->word(),
            'payment_date' => $this->faker->word(),
            'reference' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'company_id' => Company::factory(),
            'report_id' => Report::factory(),
        ];
    }
}
