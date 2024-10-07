<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('post_tag', function (Blueprint $table) {
            $table
                ->foreign('post_id')
                ->references('id')
                ->on('posts')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table
                ->foreign('tag_id')
                ->references('id')
                ->on('tags')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('post_tag', function (Blueprint $table) {
            $table->dropForeign(['post_id']);
            $table->dropForeign(['tag_id']);
        });
    }
};
