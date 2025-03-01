@extends('layout')
@section('title', 'Edit Store')

@section('content')


    <main>


        <!-- Main page content-->
        <div class="container mt-n5">


                    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
                    <div class="card">
                    <div class="card-header">Edit store</div>
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

                    <form action="{{ route('stores.update',$store->uuid) }}" method="POST" >
                        @csrf
                        @method('PUT')

                        <div class="row gx-3 mb-3">
                            <div class="col-md-6">
                                <label class="small mb-1" for="name">Name </label>
                                <input type="text" name="name" id="name" class="form-control" value="{{old('name', $store->name ?? 'Default Store Name')}}" required/>
                                @error('name')
                                    {{$message}}
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="small mb-1" for="name">User</label>
                                <input type="text" class="form-control" value="{{$store->user->first_name ?? 'N/A'}} {{$store->user->last_name ?? ''}}" name="user_id"  readonly />
                                <input type="hidden" value="{{$store->user->id ?? ''}}" name="user_id" />
                            </div>
                        </div>
                        <div class="row gx-3 mb-3">
                            <div class="col-md-6">
                                <label class="small mb-1" for="name">Section</label>
                                <select name="section_id" id="user_id" class="form-control form-control-solid" required>
                                    <option value="" >Select a section </option>
                                    @foreach($sections as $section)
                                    <option value="{{$section->id}}" {{($store->section_id ?? '') === $section->id ? 'selected':''}}>{{$section->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="small mb-1" for="description">Description </label>
                                <textarea id="description" name="description" class="form-control" required>{{old('description', $store->description ?? 'Enter store description here')}}</textarea>
                                @error('description')
                                    {{$message}}
                                @enderror
                            </div>
                        </div>

                        <div class="row gx-3 mb-3" style="margin-top: 40px;">
                            <div class="col-md-6">
                                <label class="small mb-1" for="is_online">Is online</label>
                                <input id="is_online" type="checkbox" name="is_online" {{($store->is_online ?? false) ? 'checked':''}}>
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


