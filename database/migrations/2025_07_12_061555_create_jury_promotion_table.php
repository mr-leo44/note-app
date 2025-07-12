<?php

use App\Models\Jury;
use App\Models\Promotion;
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
        Schema::create('jury_promotion', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Jury::class)->constrained();
            $table->foreignIdFor(Promotion::class)->constrained();
            $table->string('period')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jury_promotion');
    }
};
