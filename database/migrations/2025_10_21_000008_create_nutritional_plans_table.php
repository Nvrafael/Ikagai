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
        Schema::create('nutritional_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Cliente
            $table->foreignId('booking_id')->nullable()->constrained()->onDelete('set null'); // Consulta relacionada
            $table->string('title');
            $table->text('description')->nullable();
            $table->json('objectives')->nullable(); // Objetivos nutricionales
            $table->json('dietary_restrictions')->nullable(); // Restricciones alimentarias
            $table->json('meal_plan')->nullable(); // Plan de comidas en formato JSON
            $table->text('recommendations')->nullable(); // Recomendaciones generales
            $table->decimal('target_calories', 8, 2)->nullable(); // Calorías objetivo
            $table->decimal('current_weight', 8, 2)->nullable();
            $table->decimal('target_weight', 8, 2)->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('status')->default('active'); // active, completed, cancelled
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nutritional_plans');
    }
};

