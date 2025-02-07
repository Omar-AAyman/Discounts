@extends('layout')

@section('content')


    <main>


        <!-- Main page content-->
        <div class="container mt-n5">


                    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

                    <div class="card">
                    <div class="card-header">{{$section->name}}'s stores list
                   
                    </div>
                        @if (session('success'))

                        <div class="alert alert-success m-3" role="alert">{{ session('success') }}</div>
                        @endif
                        @if ($errors->has('fail'))
                            <div class="alert alert-danger m-3">
                                {{ $errors->first('fail') }}
                            </div>
                        @endif


                        @if (count($stores)==0)
                        <div class="card-body">
                        <!-- <a class="btn btn-success btn-sm mb-3" href="{{route('sections.showAttachStore',$section->uuid)}}">+add store</a> -->

                            <h4>No stores in this section</h4>
                         </div>     
                         @else
                         <div class="card-body">
                            <!-- <a class="btn btn-success btn-sm" href="{{route('sections.showAttachStore',$section->uuid)}}">+add store</a> -->
                                <table id="myTable" class="table small-table-text">
                                    <thead>
                                    <tr style="white-space: nowrap; font-size: 14px;">
                                        <th>Name</th>
                                        <th>Seller Owner</th>
                                       
                                        <th>Description</th>
                                        <th>Points</th>
                                        
                                        <th>Is Online</th>
                                        <th>Actions</th>
                                        <th></th>
                                        

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($stores  as $store )
                                        <tr style="white-space: nowrap; font-size: 14px;">

                                        <td class=" text-black"><b>{{ $store->name }}</b></td>
                                            <td>{{$store->user->first_name}} {{$store->user->last_name}}</td>
                                           
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
                                                no discription
                                                @endif            

                                            </td>   
                                            <td style="color: green;">
                                               <b>
                                                {{$store->points}}
                                                @if($loop->iteration==1 && $store->points>0) <span style="color: red;">(highest)</span>@endif
                                               </b>
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
                                        <td>
                                            @if($loop->iteration==1 && $store->points>0)
                                                           @if(!$store->user->is_sponser)     
                                                                
                                                            <form method="post" action="{{route('users.makeSponser')}}">
                                                                @csrf 
                                                                <input type="hidden" name="user_id" value="{{$store->user->id}}"/>
                                                            <button type="submit" class="btn btn-success btn-sm">make seller sponsor</button>
                                                            </form>
                                                            @else
                                                            <span style="color: green;">sponsor</span>
                                                            @endif
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


