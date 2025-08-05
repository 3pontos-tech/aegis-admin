<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\Company;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table): void {
            $table->id();
            $table->decimal('amount', 12, 2);
            $table->dateTime('date');
            $table->string('description');
            $table->string('receipt_path');
            $table->foreignIdFor(Company::class)->constrained('companies');
            $table->foreignIdFor(User::class)->constrained('users');
            $table->foreignIdFor(Report::class)->constrained('reports');
            $table->foreignIdFor(Category::class)->constrained('categories');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
