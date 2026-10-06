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
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('funteam_id')->constrained('funteams');
            $table->string('balancer_name');
            $table->string('external_id');
            $table->string('kind');
            $table->string('locale')->default('ru');
            $table->timestamps();

            $table->index(['funteam_id', 'kind'], 'idx_translations_on_funteam_id_and_kind');
            $table->unique(['balancer_name', 'external_id'], 'unq_translations_on_balancer_name_and_external_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
};
