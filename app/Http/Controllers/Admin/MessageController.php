<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller; // ✅ Add this line
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use App\Notifications\MessageNotification;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function getPriorityEnums()
{
    $result = DB::select("SHOW COLUMNS FROM messages WHERE Field = 'priority'");
    $type = $result[0]->Type;

    preg_match('/^enum\((.*)\)$/', $type, $matches);
    $values = [];

    if (isset($matches[1])) {
        foreach (explode(',', $matches[1]) as $value) {
            $val = trim($value, "'");
            $values[] = [
                'value' => $val,
                'label' => ucfirst($val) // Capitalize for label
            ];
        }
    }

    return response()->json([
        'priorities' => $values
    ]);
}
    public function send(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'priority' => 'nullable|string|max:50',
        ]);

        $message = Message::create($validated);

        $recipient = User::find($validated['user_id']);
        $recipient->notify(new MessageNotification($message));

        return response()->json(['status' => 'Message sent and notification created'], 200);
    }
}
