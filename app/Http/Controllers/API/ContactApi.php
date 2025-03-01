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

        if (!$user) {
            return response(['status' => false, 'message' => 'User doesn\'t exist'], 200);
        }

        // Check if the user has any open or in-progress tickets
        $hasOpenTicket = Ticket::where('user_id', $user->id)
            ->whereIn('status', ['open', 'in_progress'])
            ->exists();

        if ($hasOpenTicket) {
            return response(['status' => false, 'message' => 'You already have an open or in-progress ticket. Please resolve it before opening a new one.'], 200);
        }

        // Create a new ticket
        Ticket::create([
            'title' => 'Contact Message',
            'user_id' => $user->id,
            'body' => $request->message,
            'status' => 'open',
        ]);

        return response(['status' => true, 'message' => 'Message was sent to Wallet Deals admins'], 200);
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
        $ticket = Ticket::where('id', $id)->where('user_id', $user->id)->with('responses')->first();

        if (!$ticket) {
            return response(['status'=>true ,'message' => 'Ticket not found or unauthorized'], 200);
        }

        return response(['status'=>true ,'data' => $ticket],200);
    }
}
