<?php

declare(strict_types=1);

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
        Schema::create('animes', function (Blueprint $table) {
            $table->id();
            $table->string('kind');
            $table->string('rating');
            $table->string('status');
            $table->string('title');
            $table->string('slug');
            $table->date('aired_at')->nullable();
            $table->year('aired_year');
            $table->string('aired_season', 8);
            $table->date('released_at')->nullable();
            $table->smallInteger('episodes_count')->default(0);
            $table->smallInteger('episodes_aired')->default(0);
            $table->tinyInteger('duration');
            $table->dateTime('next_episode_at')->nullable();
            $table->timestamps();

            $table->unique(['id', 'slug'], 'unq_animes_on_id_and_slug');
            $table->index(['aired_year', 'aired_season'], 'idx_animes_on_aired_year_and_aired_season');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animes');
    }
};
