<?php

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
        Schema::table('result_status', function (Blueprint $table) {
            $table->string('session')->after('status')->default('S1'); // Ajout de la colonne session avec une valeur par défaut
            $table->string('period')->after('session'); // Ajout de la colonne period_id
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('result_status', function (Blueprint $table) {
            //
        });
    }
};
