@extends('layout')

@section('content')


    <main>
   

        <!-- Main page content-->
        <div class="container mt-n5">


                    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
                    <div class="card">
                    <div class="card-header">Create New Panel Option</div>
                    <div class="card-body">


                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('options.store') }}" method="POST" >
                        @csrf

                        <div class="row gx-3 mb-3">

                        <div class="col-md-6">
                        <label class="small mb-1" for="key">Key</label>
                        <input id="key" type="string" name="key" class="form-control" value="{{old('key')}}" required/>

                                
                                @error('key')
                                {{$message}} @enderror
                        </div>

                        <div class="col-md-6">
                        <label class="small mb-1" for="value">Value </label>
                        <input id="value" type="string" name="value" class="form-control" value="{{old('value')}}" required/>
                                   @error('value')
                                {{$message}}
                        @enderror
                        </div>
                        </div>




                        <div class="row gx-3 mb-3">
                        <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-sm">Create</button>
                    </div></div>
                    </form>

            </div>
        </div>
        </div>
    </main>


@endsection


