@extends('layout')

@section('content')


    <main>


        <!-- Main page content-->
        <div class="container mt-n5">


                    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">



           <div class="card">
                    <div class="card-header">Main Page Images </div>
                    @if (session('success'))

                    <div class="alert alert-success m-3" role="alert">{{ session('success') }}</div>
                    @endif
                    @if ($errors->has('fail'))
                        <div class="alert alert-danger m-3">
                            {{ $errors->first('fail') }}
                        </div>
                    @endif

                        @if ($options->isEmpty())
                        <div class="card-body">
                        
                            No Images Options
                         </div>
                        @else
                        <div class="card-body">
                                <table id="myTable" class="table small-table-text">
                                    <thead>
                                    <tr style="white-space: nowrap; font-size: 14px;">

                                        <th>Key</th>
                                        <th>Image</th>
                                      
                                        <th>Actions</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($options as $option)
                                        <tr style="white-space: nowrap; font-size: 14px;">

                                            <td class=" text-black"><b>{{ $option->key }}</b></td>
                                            <td> 
                                            @if(isset($option->img))
                                            <img src="{{ asset('optionImages/'.$option->img) }}" alt="Option Picture" width="100" height="100">
                                            @else
                                                No Image Yet 
                                            @endif   
                                        </td>
                                            
                                            <td>
                                            <a href="{{ route('options.editImageOption', $option->id ) }}" class="btn btn-primary btn-xs">Edit</a>

                                                
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
    let table = new DataTable('#myTable');
</script>
    


@endsection