@extends('layout')
@section('title', 'Edit Option')

@section('content')


    <main>


        <!-- Main page content-->
        <div class="container mt-n5">


                    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
                    <div class="card">
                    <div class="card-header">Edit Panel Option</div>
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

                    <form action="{{ route('options.update',$option->id) }}" method="POST" >
                        @csrf
                        @method('PUT')

                        <div class="row gx-3 mb-3">

                        <div class="col-md-6">
                        <label class="small mb-1" for="key">Key</label>
                        <input id="key" class="form-control" value="{{$option->key}}" readonly/>
                        <input  type="hidden" name="key"  value="{{$option->key}}" />


                                @error('key')
                                {{$message}} @enderror
                        </div>

                        <div class="col-md-6">
                        <label class="small mb-1" for="value">Value </label>
                        <input id="value" type="string" name="value" class="form-control" value="{{$option->value}}" required/>
                                   @error('value')
                                {{$message}}
                        @enderror
                        </div>
                        </div>




                        <div class="row gx-3 mb-3">
                        <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-sm">Update</button>
                    </div></div>
                    </form>

            </div>
        </div>
        </div>
    </main>


@endsection


