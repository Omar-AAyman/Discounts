@extends('layout')
@section('title', 'Sellers')

@section('content')


<main>


    <!-- Main page content-->
    <div class="container mt-n5">


        <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

        <div class="card">
            <div class="card-header">Sellers List

            </div>
            @if (session('success'))

            <div class="alert alert-success m-3" role="alert">{{ session('success') }}</div>
            @endif
            @if ($errors->has('fail'))
            <div class="alert alert-danger m-3">
                {{ $errors->first('fail') }}
            </div>
            @endif


            @if ($users->isEmpty())
            <div class="card-body">

                <h4>No Sellers</h4>
            </div>
            @else
            <div class="card-body">
                <div class="table-responsive">
                    <table id="myTable" class="table table-bordered table-hover small-table-text text-center">
                        <thead>
                            <tr style="white-space: nowrap; font-size: 14px;">

                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>City</th>
                                <th>Area</th>
                                <th>Points</th>
                                <th>Is Online</th>
                                <th>Seller Type ID</th>
                                <th>Actions</th>


                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user )
                            <tr style="white-space: nowrap; font-size: 14px;">

                                <td class=" text-black"><b>{{ $user->first_name }} {{ $user->last_name }}</b></td>
                                <td>{{ $user->email }}</td>
                                <td>{{$user->phone}}</td>
                                <td>{{$user->country_name}}</td>
                                <td>{{$user->city_name}}</td>
                                <td style="color: blue;">{{$user->points}}</td>
                                <td>
                                    <span class="badge {{ $user->is_online ? 'badge-green' : 'badge-red' }}">
                                        {{ $user->is_online ? 'Online' : 'Offline' }}
                                    </span>

                                </td>
                                <td class="text-primary">{{ $user->seller_type_id ?? 'N/A' }}</td>
                                <td>
                                    <a class="btn btn-primary btn-sm" href="{{route('users.edit' , ['uuid'=>$user['uuid'] ])}}">
                                        Edit
                                    </a>




                                </td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
            @endif


        </div>
    </div>


</main>





<script>
    let table = new DataTable('#myTable', {
        ordering: false // Disable DataTables' default ordering
    });

</script>


@endsection
