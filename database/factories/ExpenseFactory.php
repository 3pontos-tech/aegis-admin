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
        return [
            'amount' => $this->faker->word(),
            'date' => Carbon::now(),
            'description' => $this->faker->text(),
            'receipt_path' => $this->faker->image(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'company_id' => Company::factory(),
            'user_id' => User::factory(),
            'report_id' => Report::factory(),
            'category_id' => Category::factory(),
        ];
    }
}
