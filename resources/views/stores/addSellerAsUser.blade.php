@extends('layout')

@section('content')


    <main>
        <!-- Main page content-->
        <div class="container mt-n5">


                    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
                    <div class="card">
                    <div class="card-header">Add seller</div>
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

                    <form action="{{ route('stores.addSeller') }}" method="POST" >
                        @csrf
                        <input type="hidden" name="store_id" value="{{$store->id}}"/>


                        <div class="row gx-3 mb-3">

                        <div class="col-md-6">
                        <label class="small mb-1" for="name">First Name </label>
                        <input type="text" class="form-control" value="{{$store->seller_name}}" readonly />

                        </div>

                        <div class="col-md-6">
                            <label class="small mb-1" for="name">Email</label>
                            <input type="text" class="form-control" value="{{$store->email}}" readonly />


                        </div>
                    </div>
                <div class="row gx-3 mb-3">
                <div class="col-md-6">
                        <label class="small mb-1" for="last_name">Last Name</label>
                        <input type="text" name="last_name" id="last_name" class="form-control" value="{{old('last_name')}}" required />


                </div>
                        <div class="col-md-6">
                        <label class="small mb-1" for="password">Password </label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        </div>
                        </div>
                <div class="row gx-3 mb-3">

                        <div class="col-md-6">
                        <label class="small mb-1" for="password">Password Confirmation </label>

                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">

                        </div>



                        <div class="col-md-6" style="margin-top: 2rem;">
                        <button type="submit" class="btn btn-primary btn-sm">Create</button>
                    </div></div>
                    </form>

            </div>
        </div>
        </div>
    </main>


@endsection


