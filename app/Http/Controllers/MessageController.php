<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class MessageController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        // Obtener mensajes donde el usuario es el destinatario
        $receivedMessages = Message::where('receiver_id', $userId)
            ->orderBy('is_read', 'asc') // Ordenar por no leídos primero
            ->orderBy('received_at', 'desc')
            ->get();

        // Obtener mensajes donde el usuario es el remitente
        $sentMessages = Message::where('sender_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('messages.index', compact('receivedMessages', 'sentMessages'));
    }

    public function showReceived($id)
    {
        $message = Message::where('id', $id)
            ->where('receiver_id', Auth::id()) // Solo mensajes recibidos por el usuario autenticado
            ->orderBy('is_read', 'asc') // Ordenar por no leídos primero
            ->first();

        if (!$message) {
            return response()->json(['success' => false, 'error' => 'Mensaje no encontrado'], 404);
        }

        return response()->json([
            'success' => true,
            'message' => [
                'sender' => [
                    'id' => $message->sender->id,
                    'name' => $message->sender->name,
                    'lastName' => $message->sender->lastName,
                    'email' => $message->sender->email
                ],
                'subject' => $message->subject,
                'body' => $message->body,
                'shift' =>  $message->shift,
            ],
        ]);
    }

    public function showSent($id)
    {
        $message = Message::where('id', $id)
            ->where('sender_id', Auth::id()) // Solo mensajes enviados por el usuario autenticado
            ->first();

        if (!$message) {
            return response()->json(['success' => false, 'error' => 'Mensaje no encontrado'], 404);
        }

        // Desencriptar datos
        $shift = $message->shift;
        $message->subject = $this->caesarCipher($message->subject, -$shift);
        $message->body = $this->caesarCipher($message->body, -$shift);

        return response()->json([
            'success' => true,
            'message' => [
                'receiver' => $message->receiver ? [
                    'name' => $message->receiver->name,
                    'lastName' => $message->receiver->lastName
                ] : ['name' => 'Desconocido', 'lastName' => ''],
                'subject' => $message->subject,
                'body' => $message->body
            ]
        ]);
    }

    public function markAsRead($id)
    {
        Log::info("Intentando marcar como leído el mensaje ID: $id");
        $message = Message::where('id', $id)
            ->where('receiver_id', Auth::id()) // Solo el receptor puede marcarlo como leído
            ->first();

        if (!$message) {
            return response()->json([
                'success' => false,
                'error' => 'Mensaje no encontrado o no autorizado'
            ], 404);
        }

        $message->is_read = true;
        $message->save();

        return response()->json(['success' => true, 'message' => 'Mensaje marcado como leído']);
    }

    public function store(Request $request)
    {
        try {
            // Validar los datos
            $validatedData = $request->validate([
                'receiver_id' => 'required|exists:users,id',
                'subject' => 'required|string',
                'body' => 'required|string',
                'shift' => 'required|integer'
            ]);

            //Nos aseguramos de que el usuario autenticado sea el remitente
            $validatedData['sender_id'] = Auth::id();

            // Guardar el mensaje en la base de datos
            $message = Message::create([
                'receiver_id' => $validatedData['receiver_id'],
                'sender_id' => $validatedData['sender_id'],
                'subject' => $validatedData['subject'],
                'body' => $validatedData['body'],
                'shift' => $validatedData['shift'],
                'sent_at' => now(),  // Se guarda la fecha de envío
                'is_read' => false,   // Se marca como no leído
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Mensaje enviado correctamente',
                'data' => $message
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar el mensaje',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function encryptMessage(Request $request)
    {

        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'subject' => 'required|string',
            'body' => 'required|string',
            'shift' => 'required|integer',
        ]);

        $encryptedSubject = $this->caesarCipher($request->subject, (int) $request->shift);
        $encryptedBody = $this->caesarCipher($request->body, (int) $request->shift);

        $receiver = User::find($request->receiver_id);

        return response()->json([
            'success' => true,
            'receiver_id' => $receiver->id,
            'receiver_info' => "{$receiver->name} {$receiver->lastName} ({$receiver->email})",
            'subject' => $encryptedSubject,
            'body' => $encryptedBody,
            'shift' => $request->shift,
        ]);
    }

    /* Cifrado Cesar */
    private function caesarCipher($text, $shift)
    {
        $alphabetUpper = "ABCDEFGHIJKLMNÑOPQRSTUVWXYZ";
        $alphabetLower = "abcdefghijklmnñopqrstuvwxyz";
        $numbers = "0123456789";
        $result = ''; // Texto cifrado

        for ($i = 0; $i < mb_strlen($text); $i++) {
            $char = mb_substr($text, $i, 1);

            if (mb_strpos($alphabetUpper, $char) !== false) {
                // Si es mayúscula (A-Z)
                $index = (mb_strpos($alphabetUpper, $char) + $shift) % mb_strlen($alphabetUpper);
                $char = mb_substr($alphabetUpper, $index, 1);
            } elseif (mb_strpos($alphabetLower, $char) !== false) {
                // Si es minúscula (a-z)
                $index = (mb_strpos($alphabetLower, $char) + $shift) % mb_strlen($alphabetLower);
                $char = mb_substr($alphabetLower, $index, 1);
            } elseif (mb_strpos($numbers, $char) !== false) {
                // Si es un número (0-9)
                $index = (mb_strpos($numbers, $char) + $shift) % mb_strlen($numbers);
                $char = mb_substr($numbers, $index, 1);
            }

            $result .= $char; // Agrega el carácter cifrado al resultado
        }

        return $result;
    }
}
