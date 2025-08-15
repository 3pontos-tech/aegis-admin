<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;

final class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (app()->isLocal()) {
            User::factory()->admin()->create();
        }
        $company = Company::factory()->create();

        User::factory(10)
            ->recycle($company)
            ->create();
    }
}
