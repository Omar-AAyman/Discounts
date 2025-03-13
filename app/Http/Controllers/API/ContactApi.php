<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;


class ContactApi extends Controller
{

    public function userMessage(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'message' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();
        $lang = $user->lang ?? 'ar';
        if (!$user) {
            return response(['status' => false, 'message' => __('messages.user_not_found', [], $lang)], 200);
        }

        // Check if the user has any open or in-progress tickets
        $hasOpenTicket = Ticket::where('user_id', $user->id)
            ->whereIn('status', ['open', 'in_progress'])
            ->exists();

        if ($hasOpenTicket) {
            return response(['status' => false, 'message' => __('messages.ticket_already_open', [], $lang)], 200);
        }

        // Create a new ticket
        Ticket::create([
            'title' => __('messages.contact_message', [], $lang),
            'user_id' => $user->id,
            'body' => $request->message,
            'status' => 'open',
        ]);

        return response(['status' => true, 'message' => __('messages.message_sent', [], $lang)], 200);
    }

    /**
     * Get a list of tickets for the authenticated user.
     */
    public function getUserTickets(Request $request)
    {
        $user = auth()->user();

        $tickets = Ticket::where('user_id', $user->id)->with('responses')->get();

        return response(['status'=>true ,'data' => $tickets],200);

    }

    /**
     * Show a specific ticket, but only if it belongs to the authenticated user.
     */
    public function showUserTicket(Request $request, $id)
    {
        $user = auth()->user();
        $lang = $user->lang ?? 'ar';

        $ticket = Ticket::where('id', $id)->where('user_id', $user->id)->with('responses')->first();

        if (!$ticket) {
            return response(['status'=>true ,'message' => __('messages.ticket_not_found', [], $lang)], 200);
        }

        return response(['status'=>true ,'message' => __('messages.ticket_retrieved', [], $lang),'data' => $ticket],200);
    }
}
