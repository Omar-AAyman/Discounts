<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
        public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $tickets = Ticket::where('parent_id',null)->orderBy('created_at','desc')->get();
        return view('tickets.index',compact('tickets'));

    }

    public function show($id)
    {
        $ticket = Ticket::findOrFail($id);
        // Load the associated replies
        $relatedTickets = Ticket::where('parent_id', $id)->get();
        return view('tickets.show', compact('ticket', 'relatedTickets'));
    }

    public function storeReply(Request $request, $parentTicketId)
    {
        $validatedData = $request->validate([
            'reply_body' => 'required|string',
        ]);

        // Create a new ticket as a reply
        Ticket::create([
            'user_id' => auth()->user()->id, // Assuming you're using authentication
            'title' => 'reply', // You can customize this if needed
            'body' => $validatedData['reply_body'],
            'parent_id' => $parentTicketId,
        ]);

        // Redirect back to the ticket with a success message
        return redirect()->route('tickets.show', $parentTicketId)->with('success', 'Reply submitted successfully');
    }
}
