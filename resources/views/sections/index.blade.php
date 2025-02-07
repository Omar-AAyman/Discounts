@extends('layout')

@section('content')


    <main>


        <!-- Main page content-->
        <div class="container mt-n5">


                    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

                    <div class="card">
                    <div class="card-header">Sections List
                   
                    </div>
                        @if (session('success'))

                        <div class="alert alert-success m-3" role="alert">{{ session('success') }}</div>
                        @endif
                        @if ($errors->has('fail'))
                            <div class="alert alert-danger m-3">
                                {{ $errors->first('fail') }}
                            </div>
                        @endif


                        @if ($sections->isEmpty())
                        <div class="card-body">
                         
                            <h4>No sections</h4>
                         </div>     
                         @else
                         <div class="card-body">
                                <table id="myTable" class="table small-table-text">
                                    <thead>
                                    <tr style="white-space: nowrap; font-size: 14px;">

                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Belongs to package</th>
                                       
                                        
                                        <th>Is Online</th>
                                        <th>Actions</th>
                                        <th></th>
                                        

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($sections  as $section )
                                        <tr style="white-space: nowrap; font-size: 14px;">

                                            <td class=" text-black"><b>{{ $section->name }}</b></td>
                                            <td>{{$section->type}}</td>
                                            <td>{{$section->package->name}}</td>
               

                                            <td>
                                                <span class="badge {{ $section->is_online ? 'badge-green' : 'badge-red' }}">
                                                    {{ $section->is_online ? 'Online' : 'Offline' }}
                                                    </span>
                 
                                            </td>
                                            
                                            <td>
                                            <a class="btn btn-primary btn-sm" href="{{route('sections.edit' , ['uuid'=>$section['uuid'] ])}}" >   
                                            Edit
                                              </a>
                                        

                                        
                                        
                                        </td>
                                        <td>
                                        <a class="btn btn-success btn-sm" href="{{route('sections.showStores' , ['uuid'=>$section['uuid'] ])}}" >   
                                            Show stores points
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


