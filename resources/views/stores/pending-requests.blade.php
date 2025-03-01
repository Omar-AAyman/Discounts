@extends('layout')
@section('title', 'Stores Pending Requests')

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

                    <table id="myTable" class="table small-table-text text-center">
                        <thead>
                            <tr style="white-space: nowrap; font-size: 14px;">

                                <th>Store Name</th>
                                <th>Old discount (%)</th>
                                <th>New requested discount % </th>
                                <th>Controls</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($changeRequests as $changeRequest)
                            <tr style="white-space: nowrap; font-size: 14px;">
                                <td>{{ $changeRequest->store->name }}</td>
                                <td>{{ $changeRequest->old_discount_percentage }}</td>
                                <td>{{ $changeRequest->requested_discount_percentage }}</td>
                                <td>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <!-- or use 'me-2' class for specific spacing -->
                                        <form method="post" action="{{ route('stores.discount.accept', $changeRequest->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-xs mx-1">Accept</button>
                                        </form>
                                        <form method="post" action="{{ route('stores.discount.reject', $changeRequest->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-xs ">Reject</button>
                                        </form>
                                    </div>
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
