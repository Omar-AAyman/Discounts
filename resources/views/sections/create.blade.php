@extends('layout')

@section('content')


    <main>
   

        <!-- Main page content-->
        <div class="container mt-n5">


                    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
                    <div class="card">
                    <div class="card-header">Create new section</div>
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

                    <form action="{{ route('sections.store') }}" method="POST" >
                        @csrf

                        <div class="row gx-3 mb-3">

                        <div class="col-md-6">
                        <label class="small mb-1" for="name">Name </label>
                        <input type="text" name="name" id="name" class="form-control" value="{{old('name')}}" required/>
                        @error('name')
                                {{$message}}
                        @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="small mb-1" for="name">Package</label>
                            <select name="package_id" id="user_id" class="form-control form-control-solid" required>
                                        <option value="" >Select a package </option>
                                    @foreach($packages as $package)
                                    <option value="{{$package->id}}">{{$package->name}}</option>
                                    @endforeach
                                </select>
                                
                        </div>
                    </div>
                <div class="row gx-3 mb-3">
                <div class="col-md-6">
                        <label class="small mb-1" for="type">Type</label>
                        <input type="text" name="type" id="type" class="form-control" value="{{old('type')}}" required/>
                        @error('type')
                                {{$message}}
                        @enderror
                                
                </div>
                        <div class="col-md-6">
                        <label class="small mb-1" for="description">Description </label>
                        <textarea id="description"  name="description" class="form-control" required>{{old('description')}}</textarea>
                                   @error('description')
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


