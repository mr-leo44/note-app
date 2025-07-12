<?php

use App\Models\Period;
use App\Models\Student;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Period::class)->constrained();
            $table->foreignIdFor(Student::class)->constrained();
            $table->json('notes');
            $table->string('mention');
            $table->decimal('percentage', 4, 2)->default(0.00); // Assuming a percentage field for the result
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
