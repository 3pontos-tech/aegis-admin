<?php

declare(strict_types=1);

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('department_user', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(Department::class, 'department_id')->constrained('departments');
            $table->foreignIdFor(User::class, 'user_id')->constrained('users');
            $table->timestamps();
            $table->unique(['department_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('department_user');
    }
};
