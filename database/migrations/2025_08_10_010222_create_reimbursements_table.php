<?php

declare(strict_types=1);

use App\Models\Company;
use App\Models\Report;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reimbursements', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(Company::class)->constrained('companies');
            $table->foreignIdFor(Report::class)->constrained('reports');
            $table->decimal('amount', 10, 2);
            $table->string('status');
            $table->string('payment_method');
            $table->string('payment_date')->nullable();
            $table->string('reference');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reimbursements');
    }
};
