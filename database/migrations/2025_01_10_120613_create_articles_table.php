<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use function Laravel\Prompts\table;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('slug')->index();
            $table->text('description');
            $table->text('content');
            $table->string('category', 255)->nullable();
            $table->string('author', 255)->nullable();
            $table->string('source', 255);
            $table->date('published_at');

            $table->index('published_at');
            $table->index('category');
            $table->index('source');

            $table->timestamps();
        });

        if(! app()->runningUnitTests()) { /* Sqlite doesn't support full text index */

            DB::statement('ALTER TABLE articles ADD FULLTEXT title_description_content_fulltext_idx(title, description, content)');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
