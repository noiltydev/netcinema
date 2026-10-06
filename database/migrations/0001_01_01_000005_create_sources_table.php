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
        Schema::create('sources', function (Blueprint $table) {
            $table->id();
            $table->string('sourceable_type');
            $table->unsignedBigInteger('sourceable_id');
            $table->string('provider');
            $table->string('external_id');
            $table->timestamps();

            $table->index('external_id', 'idx_sources_on_external_id');
            $table->unique(
                ['sourceable_type', 'sourceable_id', 'provider'],
                'unq_sources_on_sourceable_and_provider',
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sources');
    }
};
