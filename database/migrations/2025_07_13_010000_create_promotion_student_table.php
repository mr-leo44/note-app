<?php

use App\Models\Promotion;
use App\Models\Student;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('promotion_student', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Promotion::class)->constrained();
            $table->foreignIdFor(Student::class)->constrained();
            $table->string('period');
            $table->string('status'); // enum cast côté modèle
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotion_student');
    }
};
