@extends('layout')

@section('content')


    <main>
   

        <!-- Main page content-->
        <div class="container mt-n5">


                    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
                    <div class="card">
                    <div class="card-header">Edit section</div>
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

                    <form action="{{ route('sections.update',$section->uuid) }}" method="POST" >
                        @csrf
                        @method('PUT')

                        <div class="row gx-3 mb-3">

                        <div class="col-md-6">
                        <label class="small mb-1" for="name">Name </label>
                        <input type="text" name="name" id="name" class="form-control" value="{{$section->name}}" required/>
                        @error('name')
                                {{$message}}
                        @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="small mb-1" for="name">Package</label>
                            <select name="package_id" id="user_id" class="form-control form-control-solid" required>
                                        <option value="" >Select a package </option>
                                    @foreach($packages as $package)
                                    <option value="{{$package->id}}" {{$section->package_id === $package->id ? 'selected':''}}>{{$package->name}}</option>
                                    @endforeach
                                </select>
                                
                        </div>
                    </div>
                <div class="row gx-3 mb-3">
                <div class="col-md-6">
                        <label class="small mb-1" for="type">Type</label>
                        <input type="text" name="type" id="type" class="form-control" value="{{$section->type}}" required/>
                        @error('type')
                                {{$message}}
                        @enderror
                                
                </div>
                        <div class="col-md-6">
                        <label class="small mb-1" for="description">Description </label>
                        <textarea id="description"  name="description" class="form-control" required>{{$section->description}}</textarea>
                                   @error('description')
                                {{$message}}
                        @enderror
                        </div>
                        </div>

                        <div class="row gx-3 mb-3" style="margin-top: 40px;">
                        <div class="col-md-6">
                        <label class="small mb-1" for="is_online">Is online</label>
                        <input id="is_online" type="checkbox"  name="is_online" {{$section->is_online? 'checked':''}}>

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


