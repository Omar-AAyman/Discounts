@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>
                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    {{ __('You are logged in!') }}
                    @if(auth()->user()->type=='delegate')
                    <a href="{{route('delegates.mainView')}}">Go to main view</a>
                    @elseif(auth()->user()->is_admin)
                    <a href="/admin">Admin Panel</a>

                    @elseif(auth()->user()->type=='customer_support')
                        <a href="/tickets">Go To Tickets</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
