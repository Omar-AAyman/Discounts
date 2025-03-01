@extends('layout')
@section('title', 'New Store')

@section('content')


    <main>


        <!-- Main page content-->
        <div class="container mt-n5">


                    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
                    <div class="card">
                    <div class="card-header">Create new store</div>
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

                    <form action="{{ route('stores.store') }}" method="POST" >
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
                            <label class="small mb-1" for="name">User</label>
                            <select name="user_id" id="user_id" class="form-control form-control-solid" required>
                                        <option value="" >Select a user </option>
                                    @foreach($users as $user)
                                    <option value="{{$user->id}}">{{$user->first_name}} {{$user->last_name}}</option>
                                    @endforeach
                                </select>

                        </div>
                    </div>
                <div class="row gx-3 mb-3">
                <div class="col-md-6">
                        <label class="small mb-1" for="name">Section</label>
                        <select name="section_id" id="user_id" class="form-control form-control-solid" required>
                                    <option value="" >Select a section </option>
                                    @foreach($sections as $section)
                                    <option value="{{$section->id}}">{{$section->name}}</option>
                                    @endforeach
                                </select>

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


