@extends('layout')

@section('content')


    <main>


        <!-- Main page content-->
        <div class="container mt-n5">


                    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

                    <div class="card">
                    <div class="card-header">Packages List
                   
                    </div>
                        @if (session('success'))

                        <div class="alert alert-success m-3" role="alert">{{ session('success') }}</div>
                        @endif
                        @if ($errors->has('fail'))
                            <div class="alert alert-danger m-3">
                                {{ $errors->first('fail') }}
                            </div>
                        @endif


                        @if ($packages->isEmpty())
                        <div class="card-body">
                         
                            <h4>No packages</h4>
                         </div>     
                         @else
                         <div class="card-body">
                                <table id="myTable" class="table small-table-text">
                                    <thead>
                                    <tr style="white-space: nowrap; font-size: 14px;">

                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Is Online</th>
                                        <th>Actions</th>
                                        <th></th>
                                        

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($packages  as $package )
                                        <tr style="white-space: nowrap; font-size: 14px;">

                                            <td class=" text-black"><b>{{ $package->name }}</b></td>
                                            <td>
                                                            @php
                                                                $words = explode(' ', $package->description);
                                                                echo implode(' ', array_slice($words, 0, 5));
                                                                if (count($words) > 5) {
                                                                    echo ' ...';
                                                                }
                                                            @endphp

                                            </td>   

                                            <td>
                                                <span class="badge {{ $package->is_online ? 'badge-green' : 'badge-red' }}">
                                                    {{ $package->is_online ? 'Online' : 'Offline' }}
                                                    </span>
                 
                                            </td>
                                            
                                            <td>
                                            <a class="btn btn-primary btn-sm" href="{{route('packages.edit' , ['uuid'=>$package['uuid'] ])}}" >   
                                            Edit
                                              </a>
                                        

                                        
                                        
                                        </td>
                                        <td>
                                        <a class="btn btn-success btn-sm" href="{{route('packages.showSections' , ['uuid'=>$package['uuid'] ])}}" >   
                                           Show sections 
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


