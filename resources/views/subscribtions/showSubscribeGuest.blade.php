@extends('layout')
@section('title', 'Subscribe a Guest')

@section('content')


    <main>


        <!-- Main page content-->
        <div class="container mt-n5">


                    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
                    <div class="card">
                    <div class="card-header">Subscribe a guest</div>
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

                    @if (session('subscribtionError'))

                    <div class="alert alert-danger m-3" role="alert">{{ session('subscribtionError') }}</div>
                    @endif

                    <form action="{{ route('subscriptions.guestSubscription') }}" method="POST" >
                        @csrf

                        <div class="row gx-3 mb-3">


                        <div class="col-md-6">
                            <label class="small mb-1" for="name">User</label>
                            <select name="user_id" id="user_id" class="form-control form-control-solid" required>
                                        <option value="" >Select a user </option>
                                    @foreach($users as $user)
                                    <option value="{{$user->id}}">{{$user->first_name}} {{$user->last_name}}</option>
                                    @endforeach
                                </select>

                        </div>



                        <div class="col-md-6" style="margin-top: 35px;">
                        <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                    </div></div>
                    </form>

            </div>
        </div>
        </div>
    </main>


@endsection


