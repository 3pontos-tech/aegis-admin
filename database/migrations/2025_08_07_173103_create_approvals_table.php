<?php

declare(strict_types=1);

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
        Schema::create('approvals', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(Company::class)->constrained('companies');
            $table->foreignIdFor(Report::class)->constrained('reports');
            $table->foreignIdFor(User::class, 'approver_id')->constrained('users');
            $table->string('level');
            $table->string('status');
            $table->string('comments');
            $table->dateTime('approved_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('approvals');
    }
};
