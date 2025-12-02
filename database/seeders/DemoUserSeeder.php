<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear (o buscar si ya existe) el Usuario Demo
        // Usamos firstOrCreate para evitar duplicados si corres el seeder varias veces
        $demoUser = User::firstOrCreate(
            ['email' => 'prueba@portafolio.com'], // Buscamos por este email
            [
                'name'      => 'Visitante',
                'lastName'  => 'Demo',        // Campo requerido según el modelo User.php
                'userName'  => 'usuario_demo', // Campo requerido según el modelo User.php
                'password'  => Hash::make('Demo1234'),
                'new_messages_count' => 1,    // Inicializamos el contador para que coincida con el mensaje
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // 2. Crear un usuario "Admin" o "Sistema" (remitente del mensaje de bienvenida)
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@caesartalk.com'],
            [
                'name'      => 'Admin',
                'lastName'  => 'Sistema',
                'userName'  => 'admin_bot',
                'password'  => Hash::make('AdminSecret123'),
            ]
        );

        // 3. Crear un Mensaje de Bienvenida en la bandeja del Demo
        // Verificamos si ya existe para no duplicarlo
        $existingMessage = Message::where('sender_id', $adminUser->id)
            ->where('receiver_id', $demoUser->id)
            ->where('subject', '¡Bienvenido a la Demo!')
            ->first();

        if (!$existingMessage) {
            Message::create([
                'sender_id'   => $adminUser->id,
                'receiver_id' => $demoUser->id,
                'subject'     => '¡Bienvenido a la Demo!',
                // Como tu sistema usa cifrado César, aquí podrías poner el texto ya cifrado
                // O texto plano con shift 0 si tu sistema lo soporta.
                'body'        => 'Gracias por probar mi sistema CaesarTalk. Sientete libre de enviar mensajes.',
                'shift'       => 0, // Desplazamiento 0 para que sea legible sin descifrar (o ajústalo según tu lógica)
                'sent_at'     => Carbon::now(),
                'received_at' => Carbon::now(), // Ya recibido
                'is_read'     => false,
            ]);
        }
    }
}
