<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Mostrar bandeja de mensajes
     */
    public function index()
    {
        $userId = Auth::id();

        // Obtener lista de conversaciones (usuarios con los que ha tenido conversaciones)
        $conversations = Message::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->with(['sender', 'receiver'])
            ->get()
            ->groupBy(function ($message) use ($userId) {
                return $message->sender_id === $userId ? $message->receiver_id : $message->sender_id;
            })
            ->map(function ($messages) {
                return $messages->first();
            });

        return view('messages.index', compact('conversations'));
    }

    /**
     * Mostrar conversación con un usuario específico
     */
    public function show(User $user)
    {
        $messages = Message::conversation(Auth::id(), $user->id);

        // Marcar mensajes como leídos
        Message::where('sender_id', $user->id)
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return view('messages.show', compact('messages', 'user'));
    }

    /**
     * Enviar nuevo mensaje
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:5120', // 5MB máximo por archivo
        ]);

        $validated['sender_id'] = Auth::id();

        // Procesar archivos adjuntos
        if ($request->hasFile('attachments')) {
            $attachments = [];
            foreach ($request->file('attachments') as $file) {
                $attachments[] = $file->store('messages', 'private');
            }
            $validated['attachments'] = $attachments;
        }

        $message = Message::create($validated);

        return back()->with('success', 'Mensaje enviado exitosamente.');
    }

    /**
     * Marcar mensaje como leído
     */
    public function markAsRead(Message $message)
    {
        if ($message->receiver_id !== Auth::id()) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }

        $message->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Obtener mensajes no leídos
     */
    public function unreadCount()
    {
        $count = Message::where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Eliminar mensaje
     */
    public function destroy(Message $message)
    {
        // Verificar que el usuario sea el remitente o destinatario
        if ($message->sender_id !== Auth::id() && $message->receiver_id !== Auth::id()) {
            abort(403, 'No tienes permiso para eliminar este mensaje.');
        }

        $message->delete();

        return back()->with('success', 'Mensaje eliminado exitosamente.');
    }

    /**
     * Iniciar conversación con nutricionista (para clientes)
     */
    public function newConversation()
    {
        $nutritionist = User::where('role', 'nutritionist')->first();

        if (!$nutritionist) {
            return back()->with('error', 'No hay nutricionista disponible.');
        }

        return redirect()->route('messages.show', $nutritionist);
    }
}

