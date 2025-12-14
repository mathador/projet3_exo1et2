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
        Schema::table('notes', function (Blueprint $table) {
            // Drop the existing foreign key
            $table->dropForeign(['tag_id']);

            // Add the foreign key again with ON DELETE RESTRICT
            $table->foreign('tag_id')
                  ->references('id')
                  ->on('tags')
                  ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            // Drop the foreign key with RESTRICT
            $table->dropForeign(['tag_id']);

            // Add the foreign key back with ON DELETE CASCADE
            $table->foreign('tag_id')
                  ->references('id')
                  ->on('tags')
                  ->onDelete('cascade');
        });
    }
};
