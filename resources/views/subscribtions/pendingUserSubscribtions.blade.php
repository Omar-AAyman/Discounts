@extends('layout')
@section('title', 'Pending Subscriptions')

@section('content')


<main>


    <!-- Main page content-->
    <div class="container mt-n5">


        <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

        <div class="card">
            <div class="card-header">Pending Subscriptions

            </div>
            @if (session('success'))

            <div class="alert alert-success m-3" role="alert">{{ session('success') }}</div>
            @endif
            @if ($errors->has('fail'))
            <div class="alert alert-danger m-3">
                {{ $errors->first('fail') }}
            </div>
            @endif


            @if ($subscribtions->isEmpty())
            <div class="card-body">
                {{-- <a class="btn btn-success btn-sm mb-3" href="{{route('subscriptions.showSubscribeUser')}}">subscribe a user</a> --}}

                <h4>No pending subscriptions</h4>
            </div>
            @else
            <div class="card-body">
                {{-- <a class="btn btn-success btn-sm mb-2" href="{{route('subscriptions.showSubscribeUser')}}">subscribe a user</a> --}}
                <div class="table-responsive">

                    <table id="myTable" class="table small-table-text text-center">
                        <thead>
                            <tr style="white-space: nowrap; font-size: 14px;">

                                <th>User Name</th>
                                <th>User Phone</th>
                                <th>Package</th>
                                <th>Created At</th>
                                <th>Subscription Period</th>
                                <th>Status</th> <!-- New Status Column -->
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($subscribtions as $subscribtion)
                            @php
                            $createdAt = \Carbon\Carbon::parse($subscribtion->created_at);
                            $status = $createdAt->diffInMinutes(now()) > 30 ? 'Expired' : 'Pending Payment';
                            $badgeClass = $status == 'Expired' ? 'bg-secondary' : 'bg-warning ';
                            @endphp
                            <tr style="white-space: nowrap; font-size: 14px;">
                                <td class="text-black"><b>{{ $subscribtion->user->first_name }} {{ $subscribtion->user->last_name }}</b></td>
                                <td>{{ $subscribtion->user->phone }}</td>
                                <td>{{ $subscribtion->package->name }}</td>
                                <td>{{ $createdAt->format('F j, Y g:i A') }}</td>
                                <td>{{ $subscribtion->period_in_months ? $subscribtion->period_in_months . ' Months' : 'N/A' }}</td>
                                <td>
                                    <span class="badge {{ $badgeClass }} text-white px-2">{{ $status }}</span>
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

<script>
    function confirmUnsubscribe(event) {
        if (!confirm("Are you sure you want to unsubscribe this user?")) {
            event.preventDefault(); // Prevent form submission if the user clicks "Cancel"
        }
    }

</script>
@endsection
