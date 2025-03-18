<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use function Laravel\Prompts\alert;

class NavigationController extends Controller
{
    //Se encarga de devolver un mensaje de bienvenida al usuario
    public function getWelcomeMessage()
    {
        $user = Auth::user();

        if ($user && !session('welcome_message_shown')) {
            // 1. Contar mensajes no leídos (sin received_at)
            $newMessagesCount = Message::where('receiver_id', $user->id)
                ->whereNull('received_at')
                ->count();

            Log::info('last_login_at: ' . $user->last_login_at);
            Log::info('last_login_before: ' . $user->last_login_before);

            // 2. Actualizar received_at

            $nullMessages = Message::where('receiver_id', $user->id)
                ->whereNull('received_at')
                ->where('sent_at', '<', $user->last_login_at)
                ->get();

            foreach ($nullMessages as $nullMessage) {
                $nullMessage->received_at = $user->last_login_at;
                $nullMessage->update(['received_at' => $user->last_login_at]);
                $nullMessage->save();
            }

            // 3. Crear el mensaje de bienvenida
            if (session('first_login_at') !== null) {
                $message = [
                    'welcome_message' => '¡Bienvenido a nuestra aplicación!',
                    'new_messages_count' => $newMessagesCount,
                ];
                if ($user instanceof User) {
                    // Guarda el valor de last_login_at en last_login_before
                    $user->update(['last_login_before' => session('first_login_at')]);
                    $user->save();
                }
                // Elimina la sesión
                session()->forget('first_login_at');
            } else {

                $message = [
                    'last_login_at_friendly' => Carbon::parse($user->last_login_before)->diffForHumans(),
                    'last_login_at_detailed' => Carbon::parse($user->last_login_before)->toDateTimeString(),
                    'new_messages_count' => $newMessagesCount,
                ];
                if ($user instanceof User) {
                    $user->update(['last_login_before' => $user->last_login_at]);
                    $user->save();
                }
            }

            session(['welcome_message_shown' => true]);

            return response()->json(['message' => $message]);
        } else {
            return response()->json(['message' => null]);
        }
    }

    //Se encarga solamente de consultar a la base de datos y devolver al navbar los mensajes no leídos
    public function countUnreadMessages()
    {
        $unreadMessages = Message::where('receiver_id', Auth::id())->where('is_read', false)->count();
        return response()->json(['count' => $unreadMessages]);
    }
}
