<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('news_sources', function (Blueprint $table) {
            $table->id();

            /* These fields map search params for each service */
            $table->string('name');
            $table->string('url');
            $table->string('from_param');
            $table->string('sortBy_param');
            $table->string('pageSize_param');
            $table->string('query_param');
            $table->string('apiKey_param');

            /* These fields map body fields for each service */
            $table->string('title_field', 255);
            $table->string('description_field');
            $table->string('content_field');
            $table->string('category_field')->nullable();
            $table->string('author_field');
            $table->string('source_field');
            $table->string('published_at_field');
            $table->tinyInteger('is_active');
        });

        DB::table('news_sources')->insert([
            'name' => 'NewsAPI',
            'url' => 'https://newsapi.org/v2/everything',
            'from_param' => 'from',
            'sortBy_param' => 'sortBy',
            'pageSize_param' => 'pageSize',
            'query_param' => 'q',
            'apiKey_param' => 'apiKey',
            'title_field' => 'title',
            'description_field' => 'description',
            'content_field' => 'content',
            'category_field' => null,
            'author_field' => 'author',
            'published_at_field' => 'publishedAt',
            'source_field' => 'source.name',
            'is_active' => 1
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_sources');
    }
};
