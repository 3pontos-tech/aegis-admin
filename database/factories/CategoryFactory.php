<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Category;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

final class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $categories = $this->faker
            ->unique()
            ->randomElement(['Food', 'Transportation', 'Entertainment', 'Health', 'Education', 'Other']);

        return [
            'name' => $categories,
            'description' => $this->faker->text(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'company_id' => Company::factory(),
        ];
    }
}
