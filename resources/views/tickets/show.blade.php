@extends('layout')

@section('content')


    <main>


        <!-- Main page content-->
        <div class="container mt-n5">

        @if (session('success'))

       <div class="alert alert-success m-3" role="alert">{{ session('success') }}</div>
       @endif


            


                    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

                    <div class="row gx-4">
                    <div class="col-12">
                    <div class="card mb-4">
                    <div class="card-header">
                    {{ $ticket->title }}
                    By User {{ $ticket->user->first_name }} {{ $ticket->user->last_name }}
                    </div>

                    <div class="card-body">
                        <input class="form-control"  type="text" value="{{ $ticket->body }}" readonly />
                       
                    </div>
                    </div>
                    

                   

                                    <div class="card card-header-actions mb-4">
                                    <div class="card-header">
                                        Replies
                                    <i class="text-muted" data-feather="info" data-bs-toggle="tooltip" data-bs-placement="left" title="The post preview text shows below the post title, and is the post summary on blog pages."></i>
                                    </div>
                                    <div class="card-body">
                                        <ul>
                                            @foreach ($relatedTickets as $relatedTicket)
                                                <li>
                                                    {{$relatedTicket->body}}
                                                
                                                </li>
                                            @endforeach
                                        </ul>

                    <h4>Reply to this Ticket</h4>
                    <form method="POST" action="{{ route('tickets.storeReply', $ticket->id) }}">
                        @csrf
                        <div>
                            
                            
                        <textarea class="form-control" name="reply_body" id="reply_body"required></textarea>
                        @error('reply_body')
                                    {{$message}}
                                    @enderror
                    </div>
                        <div>
                            <br>
                            <button type="submit">Submit Reply</button>
                        </div>
                    </form>


                </div>
            </div>
    </main>

 


@endsection


