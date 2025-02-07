@extends('layout-delegate')

@section('content')


    <main>


        <!-- Main page content-->
        <div class="container mt-n5">


                    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

                    <div class="card">
                    <div class="card-header">Add Seller 
                   
                    </div>
                        @if (session('success'))

                        <div class="alert alert-success m-3" role="alert">{{ session('success') }}</div>
                        @endif
                        @if ($errors->has('fail'))
                            <div class="alert alert-danger m-3">
                                {{ $errors->first('fail') }}
                            </div>
                        @endif


                        
                        <div class="card-body">
                         
                           <a href="{{route('delegates.createSeller')}}" class="btn btn-success btn-sm">Add Seller</a>
                         </div>     
                        

                       
                    </div>
                </div>

        
    </main>



@endsection


