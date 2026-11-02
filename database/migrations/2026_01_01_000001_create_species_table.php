<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('species', function (Blueprint $table): void {
            $table->id();
            $table->string('common_name');
            $table->string('scientific_name')->nullable();
            $table->string('family');
            $table->enum('sun_exposure', ['full_sun', 'partial_shade', 'full_shade']);
            $table->enum('water_demand', ['low', 'medium', 'high']);
            $table->unsignedInteger('min_root_depth_cm');
            $table->unsignedInteger('recommended_volume_liters');
            $table->unsignedInteger('days_to_germination');
            $table->unsignedInteger('days_to_harvest');
            $table->json('companion_allies')->nullable();
            $table->json('companion_antagonists')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('species');
    }
};

