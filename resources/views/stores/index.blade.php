@extends('layout')

@section('content')


    <main>


        <!-- Main page content-->
        <div class="container mt-n5">


                    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

                    <div class="card">
                    <div class="card-header">Stores List
                   
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
                         
                            <h4>No stores</h4>
                         </div>     
                         @else
                         <div class="card-body">
                                <table id="myTable" class="table small-table-text">
                                    <thead>
                                    <tr style="white-space: nowrap; font-size: 14px;">

                                        <th>Name</th>
                                        <th>User</th>
                                        <th>Section</th>
                                        <th>Description</th>
                                        
                                        <th>Is Online</th>
                                        <th>Actions</th>
                                        

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($stores  as $store )
                                        <tr style="white-space: nowrap; font-size: 14px;">

                                            <td class=" text-black"><b>{{ $store->name }}</b></td>
                                            <td>{{$store->user->first_name}} {{$store->user->last_name}}</td>
                                            <td>{{$store->section->name}}</td>
                                            <td>
                                                @if(isset($store->description))
                                                            @php
                                                                $words = explode(' ', $store->description);
                                                                echo implode(' ', array_slice($words, 0, 5));
                                                                if (count($words) > 5) {
                                                                    echo ' ...';
                                                                }
                                                            @endphp
                                                @else 
                                                no description
                                                @endif            

                                            </td>   

                                            <td>
                                                <span class="badge {{ $store->is_online ? 'badge-green' : 'badge-red' }}">
                                                    {{ $store->is_online ? 'Online' : 'Offline' }}
                                                    </span>
                 
                                            </td>
                                            
                                            <td>
                                            <a class="btn btn-primary btn-sm" href="{{route('stores.edit' , ['uuid'=>$store['uuid'] ])}}" >   
                                            Edit
                                              </a>
                                        

                                        
                                        
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


