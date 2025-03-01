@extends('layout')

@section('content')


<main>


    <!-- Main page content-->
    <div class="container mt-n5">


        <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
        <div class="card">
            <div class="card-header">Create new user</div>
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

                <form action="{{ route('users.store') }}" method="POST">
                    @csrf

                    <div class="row gx-3 mb-3">

                        <div class="col-md-6">
                            <label class="small mb-1" for="first_name">First Name </label>
                            <input type="text" name="first_name" id="first_name" class="form-control" value="{{old('first_name')}}" required />
                            @error('first_name')
                            {{$message}}
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="small mb-1" for="last_name">Last Name </label>
                            <input type="text" name="last_name" id="last_name" class="form-control" value="{{old('last_name')}}" required />
                            @error('last_name')
                            {{$message}}
                            @enderror
                        </div>
                    </div>
                    <div class="row gx-3 mb-3">
                        <div class="col-md-6">
                            <label class="small mb-1" for="email">Email </label>
                            <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{old('email')}}" required />
                            @error('email')
                            {{$message}}
                            @enderror
                        </div>



                        <div class="col-md-6">
                            <label class="small mb-1" for="password">Password</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                            @error('password')
                            {{$message}}
                            @enderror
                        </div>
                    </div>
                    <div class="row gx-3 mb-3">
                        <div class="col-md-6">
                            <label class="small mb-1" for="password">Password Confirmation</label>
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">

                        </div>


                        <div class="col-md-6">
                            <label class="small mb-1" for="phone">Phone</label>
                            <input id="phone" type="text" class="form-control" name="phone" required value="{{old('phone')}}">

                        </div>
                    </div>



                    <div class="row gx-3 mb-3">


                        <div class="col-md-6">
                            <label class="small mb-1" for="phone">Type</label>
                            <select name="type" class="form-control form-control-solid">
                                <option value="">Select a user type </option>
                                <option value="client">Client</option>
                                <option value="delegate">Delegate</option>
                                <option value="customer_support">Customer Support</option>
                            </select>
                        </div>



                        <div class="col-md-6" style="margin-top: 2rem;">
                            <button type="submit" class="btn btn-primary btn-sm">Create</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</main>


@endsection
