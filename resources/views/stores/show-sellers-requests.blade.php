@extends('layout')

@section('content')


    <main>


        <!-- Main page content-->
        <div class="container mt-n5">


                    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

                    <div class="card">
                    <div class="card-header">Pending Adding Sellers Requests
                   
                    </div>
                        @if (session('success'))

                        <div class="alert alert-success m-3" role="alert">{{ session('success') }}</div>
                        @endif
                        @if ($errors->has('fail'))
                            <div class="alert alert-danger m-3">
                                {{ $errors->first('fail') }}
                            </div>
                        @endif


                        @if ($stores->isEmpty())
                        <div class="card-body">
                         
                            <h4>No requests yet</h4>
                         </div>     
                         @else
                         <div class="card-body">
                                <table id="myTable" class="table small-table-text">
                                    <thead>
                                    <tr style="white-space: nowrap; font-size: 14px;">

                                        <th>Delegate Name</th>
                                        <th>Seller Name</th>
                                        <th>Store</th>
                                        <th>Section</th>
                                        <th></th>
                                        
                                       
                                        

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($stores  as $store )
                                        @php
                                         $delegate = DB::table('users')->where('id',$store->delegate_id)->first();
                                          @endphp
                                        <tr style="white-space: nowrap; font-size: 14px;">

                                            <td class=" text-black"><b>{{ $delegate->first_name }} {{ $delegate->last_name }}</b></td>
                                            <td class=" text-black"><b>{{$store->seller_name}}</b></td>
                                            <td>{{$store->name}}</td>
                                            <td>{{$store->section->name}}</td>
               

                                            
                                            <td>
                                            <form method="get" action="{{route('stores.approveSeller')}}">
                                                @csrf 
                                                <input type="hidden" name="store_id" value="{{$store->id}}"/>
                                                <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                            </form>
                                        

                                        
                                        
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


