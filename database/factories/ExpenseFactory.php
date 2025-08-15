<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Category;
use App\Models\Company;
use App\Models\Expense;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

final class ExpenseFactory extends Factory
{
    protected $model = Expense::class;

    public function definition(): array
    {
        $description = $this->faker->randomElement(['Uber', 'Breakfast', 'Lunch', 'Gas', 'Taxi']);
        $amount = $this->faker->randomElement([50_00, 70_00, 100_00, 200_00, 300_00]);

        return [
            'amount' => $amount,
            'date' => Carbon::now(),
            'description' => $description,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'company_id' => Company::factory(),
            'user_id' => User::factory(),
            'report_id' => Report::factory(),
            'category_id' => Category::factory(),
        ];
    }
}
