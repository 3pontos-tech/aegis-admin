<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Company;
use App\Models\Expense;
use App\Models\Report;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
        ]);

        $company = Company::factory()
            ->has(Category::factory())
            ->create();

        Report::factory(2)
            ->recycle($company)
            ->draft()
            ->has(Expense::factory()
                ->recycle($company->categories->random())
                ->count(2)
            )->create();
    }
}
