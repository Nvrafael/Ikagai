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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Cliente
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->dateTime('scheduled_at'); // Fecha y hora de la cita
            $table->string('status')->default('pending'); // pending, confirmed, completed, cancelled
            $table->text('notes')->nullable(); // Notas del cliente
            $table->text('nutritionist_notes')->nullable(); // Notas del nutricionista
            $table->decimal('price', 10, 2); // Precio en el momento de la reserva
            $table->string('payment_status')->default('pending'); // pending, paid, refunded
            $table->string('meeting_link')->nullable(); // Link de la reunión virtual
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
