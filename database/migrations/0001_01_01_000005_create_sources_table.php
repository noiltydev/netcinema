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
            $table->string('provider_name');
            $table->string('external_id', 64);
            $table->timestamps();

            $table->unique(
                ['sourceable_type', 'provider_name', 'external_id'],
                'unq_sources_on_sourceable_type_and_provider_name_and_external_id',
            );

            $table->index(
                ['sourceable_type', 'sourceable_id'],
                'idx_sources_on_sourceable',
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
