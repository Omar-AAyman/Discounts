@extends('layout')
@section('title', 'Show Ticket')

@section('content')

<main class="container mt-5">
    @if (session('success'))
    <div class="alert alert-success d-flex align-items-center p-2 shadow-sm">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
    </div>
    @endif

    <div class="row g-4">
        <!-- Left Column - Ticket Details & Status -->
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <h3 class="fw-bold text-primary">{{ ucfirst($ticket->title) }}</h3>
                    <p class="text-muted">
                        <i class="far fa-user-circle"></i>
                        <a href="{{ route('users.edit', $ticket->user->uuid) }}" class="text-dark fw-semibold">
                            {{ ucfirst($ticket->user->fullname) }}
                        </a>
                        <span class="text-muted"> • {{ $ticket->created_at->diffForHumans() }}</span>
                    </p>
                    <div class="border p-3 rounded-3 bg-light text-dark shadow-sm">
                        <p class="mb-0">{{ $ticket->body }}</p>
                    </div>
                </div>
            </div>

            <div class="card shadow-lg border-0 rounded-4 overflow-hidden mt-3">
                <div class="card-body p-4">
                    <h5 class="fw-bold">Update Status</h5>
                    <form action="{{ route('tickets.updateStatus', $ticket->id) }}" method="POST" class="mt-3">
                        @csrf
                        @method('PATCH')
                        <select name="status" id="status" class="form-select w-auto d-inline-block rounded-pill shadow-sm" onchange="this.form.submit()">
                            <option value="" {{ is_null($ticket->status) ? 'selected' : '' }}>Select Status</option>
                            <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Open</option>
                            <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Column - Replies -->
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-dark p-4">
                    <h5 class="mb-0 text-white">Replies</h5>
                </div>
                <div class="card-body p-4">
                    @if ($relatedTickets->isEmpty())
                    <p class="text-muted text-center">No replies yet.</p>
                    @else
                    <ul class="list-unstyled">
                        @foreach ($relatedTickets as $relatedTicket)
                        <li class="mb-3 p-3 border rounded-3 bg-light shadow-sm">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="fas fa-user-circle fa-2x text-primary"></i>
                                </div>
                                <div>
                                    <a href="{{ route('users.edit', $relatedTicket->user->uuid) }}" class="fw-bold text-primary">
                                        {{ ucfirst($relatedTicket->user->fullname) }}
                                    </a>
                                    <span class="text-muted"> • {{ $relatedTicket->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <div class="mt-2">
                                <p class="mb-0 text-dark">{{ $relatedTicket->body }}</p>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    @endif

                    <!-- Reply Form -->
                    <h4 class="mt-4">Reply to this Ticket</h4>
                    <form method="POST" action="{{ route('tickets.storeReply', $ticket->id) }}" class="mt-3">
                        @csrf
                        <div class="mb-3">
                            <textarea class="form-control rounded-3 shadow-sm" name="reply_body" id="reply_body" rows="3" placeholder="Write your reply..." required></textarea>
                            @error('reply_body')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary rounded-pill shadow-sm px-4">
                            <i class="fas fa-reply"></i> Submit Reply
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection
