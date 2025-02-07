@extends('layout')

@section('content')


    <main>


        <!-- Main page content-->
        <div class="container mt-n5">


                    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

                    <div class="card">
                    <div class="card-header">Users Subscribtion List 
                   
                    </div>
                        @if (session('success'))

                        <div class="alert alert-success m-3" role="alert">{{ session('success') }}</div>
                        @endif
                        @if ($errors->has('fail'))
                            <div class="alert alert-danger m-3">
                                {{ $errors->first('fail') }}
                            </div>
                        @endif


                        @if ($subscribtions->isEmpty())
                        <div class="card-body">
                        <a class="btn btn-success btn-sm mb-3" href="{{route('subscriptions.showSubscribeUser')}}">subscribe a user</a>

                            <h4>No users subscribtion</h4>
                         </div>     
                         @else
                         <div class="card-body">
                         <a class="btn btn-success btn-sm mb-2" href="{{route('subscriptions.showSubscribeUser')}}">subscribe a user</a>

                                <table id="myTable" class="table small-table-text">
                                    <thead>
                                    <tr style="white-space: nowrap; font-size: 14px;">

                                        <th>User Name</th>
                                        <th>User Phone</th>
                                        <th>Package</th>
                                        <th>Subscription Start Date</th>
                                        <th>Subscription Period</th>
                                        <th>Status</th>
                                        
                                        <th></th>
                                        

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($subscribtions  as $subscribtion )
                                        <tr style="white-space: nowrap; font-size: 14px;">

                                            <td class=" text-black"><b>{{ $subscribtion->user->first_name }} {{ $subscribtion->user->last_name }}</b></td>
                                            <td>
                                            {{ $subscribtion->user->phone }}

                                            </td>   
                                            <td>
                                                {{$subscribtion->package->name}}
                                            </td>
                                            <td>
                                            {{ $subscribtion->created_at->format('F j, Y') }}
                                            </td>
                                            <td>
                                                {{$subscribtion->period_in_months}} months
                                            </td>

                                            <td>
                                                <span class="badge {{ $subscribtion->is_online ? 'badge-green' : 'badge-red' }}">
                                                    {{ $subscribtion->is_online ? 'ACTIVE' : 'ENDED' }}
                                                    </span>
                 
                                            </td>
                                            
                                         <td>
                                            @if($subscribtion->is_online)
                                            <form method="post" action="{{route('subscriptions.unsubscribe')}}">
                                                @csrf 
                                                @method('PUT')
                                                <input type="hidden" name="userId" value="{{$subscribtion->user_id}}"/>
                                                <button type="submit" class="btn btn-danger btn-sm">unsubscribe</button>
                                            </form>
                                            @endif
                                         </td>
                                        

                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                            </div>
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


