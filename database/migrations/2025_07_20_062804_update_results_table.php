<?php

use App\Models\ResultSession;
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
        Schema::table('results', function (Blueprint $table) {
            // $table->dropColumn('session');
            // $table->dropForeign(['period_id']);
            // $table->dropColumn('period_id');
            $table->foreignIdFor(ResultSession::class)->after('notes')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('results', function (Blueprint $table) {
            //
        });
    }
};
