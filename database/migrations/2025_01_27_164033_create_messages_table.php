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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->string('subject'); // Asunto del mensaje
            $table->text('body'); // Cuerpo del mensaje
            $table->integer('shift'); // Desplazamiento para cifrado César
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade'); // ID del remitente
            $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade'); // ID del destinatario
            $table->timestamp('sent_at')->nullable(); // Fecha de envío
            $table->timestamp('received_at')->nullable(); // Fecha de recepción
            $table->boolean('is_read')->default(false); // Indica si el mensaje fue leído
            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
