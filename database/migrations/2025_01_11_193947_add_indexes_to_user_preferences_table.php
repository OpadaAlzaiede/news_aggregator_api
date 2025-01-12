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
        Schema::table('user_preferences', function (Blueprint $table) {
            $table->index('preference_type', 'user_preferences_preference_type_idx');
            $table->index('created_at', 'user_preferences_created_at_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_preferences', function (Blueprint $table) {
            $table->dropIndex('user_preferences_preference_type_idx');
            $table->dropIndex('user_preferences_created_at_idx');
        });
    }
};
