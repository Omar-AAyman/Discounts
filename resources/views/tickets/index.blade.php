@extends('layout')

@section('content')


    <main>


        <!-- Main page content-->
        <div class="container mt-n5">




                    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

                    <div class="card">
                    <div class="card-header">Tickets List List</div>

                    @if (session('success'))

                        <div class="alert alert-success m-3" role="alert">{{ session('success') }}</div>
                        @endif
                        @if ($errors->has('fail'))
                                <div class="alert alert-danger">
                                    {{ $errors->first('fail') }}
                                </div>
                            @endif

                        @if ($tickets->isEmpty())
                        <div class="card-body">
                         <form method="GET" action="{{route('tickets.create')}}">
                             <div class="col-md-6">
                             <label class="small mb-1 mr-5" for="max_products">No Tickets</label>
                             <button type="submit" class="btn btn-primary btn-xs">Add Ticket</button>
                             </div>
                         </form>
                         </div>
                        @else
                        <div class="card-body">
                        <br>
                        <br>
                            <table id="myTable" class="table small-table-text">
                                    <thead>
                                    <tr style="white-space: nowrap; font-size: 14px;">
                                    <th>Ticket Title</th>
                                    <th>Ticket Body</th>
                                    <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($tickets as $ticket)
                                    <tr style="white-space: nowrap; font-size: 14px;">

                                    <td class=" text-black"><b>{{$ticket->title}}</b></td>
                                    <td>
                                            @php
                                                $words = explode(' ', $ticket->body);
                                                $body = implode(' ', array_slice($words, 0, 9)).'...';
                                            @endphp
                                                    {{$body}}
                            
                            </td>
                                    <td>
                                    <a href="{{route('tickets.show',['id'=>$ticket['id']])}}"
                                    class="btn btn-primary btn-xs">
                                    Show Replies  
                                   
                                    </a>
                                    </td>
                                    </tr>


                           
                            @endforeach
                            </tbody>
                            </table>
                            @endif

                       
                    </div>
        </div>
    </main>

<script>
    let table = new DataTable('#myTable', {
        ordering: false // Disable DataTables' default ordering
    });
</script>  
@endsection        
                        







