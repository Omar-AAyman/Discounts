@extends('layout')
@section('content')

<main>
    <!-- Main page content -->
    <div class="container mt-n5">

        <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

        <div class="card">
            <div class="card-header">Discount change pending requests</div>
            <div class="card-body">

                @if ($changeRequests->isEmpty())
                    <p>No Requests.</p>
                @else
                    <div class="mt-3 table-container">
                    @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('fail'))
                <div class="alert alert-danger">
                    {{ session('fail') }}
                </div>
            @endif

                        <table id="myTable" class="table small-table-text">
                            <thead>
                                <tr style="white-space: nowrap; font-size: 14px;">
                                   
                                    <th>offer belongs to store</th>
                                    <th>Old discount (%)</th>
                                    <th>New requested discount % </th>
                                  
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($changeRequests as $changeRequest)
                                    <tr style="white-space: nowrap; font-size: 14px;">
                                        <td>{{ $changeRequest->offer->store->name }}</td>
                                        <td>{{ $changeRequest->offer->discount_percentage }}</td>
                                        <td>{{ $changeRequest->new_discount_percentage }}</td>
                                        
                                       
                                        <td>
                                            <form method="post" action="{{ route('offerNotifications.accept', $changeRequest->id) }}">
                                                @csrf 
                                                <button type="submit" class="btn btn-success btn-xs">Accept</button>
 
                                            </form>

                                        </td>
                                                       
                                        <td>
                                            <form method="post" action="{{ route('offerNotifications.reject', $changeRequest->id) }}">
                                                @csrf 
                                                <button type="submit" class="btn btn-danger btn-xs">Reject</button>
 
                                            </form>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</main>

<script>
    let table = new DataTable('#myTable', {
        ordering: false // Disable DataTables' default ordering
    });
</script>

@endsection
