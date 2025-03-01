@extends('layout')
@section('title', 'Edit Package')

@section('content')


    <main>


        <!-- Main page content-->
        <div class="container mt-n5">


                    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
                    <div class="card">
                    <div class="card-header">Create new package</div>
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

                    <form action="{{ route('packages.update',$package->uuid) }}" method="POST" >
                        @csrf
                        @method('PUT')

                        <div class="row gx-3 mb-3">

                        <div class="col-md-6">
                        <label class="small mb-1" for="name">Name </label>
                        <input type="text" name="" id="name" class="form-control" value="{{$package->name}}" disabled/>
                        @error('name')
                                {{$message}}
                        @enderror
                        </div>

                        <div class="col-md-6">
                        <label class="small mb-1" for="description">Description </label>
                        <textarea id="description"  name="description" class="form-control" required>{{$package->description}}</textarea>
                                   @error('description')
                                {{$message}}
                        @enderror
                        </div>
                        </div>




                        <div class="row gx-3 mb-3" style="margin-top: 40px;">
                        <div class="col-md-6">
                        <label class="small mb-1" for="is_online">Is online</label>
                        <input id="is_online" type="checkbox"  name="is_online" {{$package->is_online? 'checked':''}}>

                        </div>
                        <div class="col-md-6">
                        <button type="submit" class="btn btn-primary btn-sm">Update</button>
                    </div></div>
                    </form>

            </div>
        </div>
        </div>
    </main>


@endsection


