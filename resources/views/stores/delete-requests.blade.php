@extends('layout')
@section('title', 'Stores Delete Requests')

@section('content')

<main>
    <div class="container mt-n5">
        <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

        <div class="card">
            <div class="card-header">Stores Pending Deletion Requests</div>
            <div class="card-body">

                @if ($stores->isEmpty())
                <p>No deletion requests at the moment.</p>
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
                    <div class="table-responsive">

                        <table id="deleteRequestsTable" class="table small-table-text text-center">
                            <thead>
                                <tr style="white-space: nowrap; font-size: 14px;">
                                    <th>Store Name</th>
                                    <th>Seller Name</th>
                                    <th>Requested By</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($stores as $store)
                                <tr style="white-space: nowrap; font-size: 14px;">
                                    <td>{{ $store->name }}</td>
                                    <td>{{ $store->user->fullname }}</td>
                                    <td>{{ $store->delegate->fullname ?? 'N/A' }}</td>
                                    <td>
                                        <div class="d-flex gap-2 justify-content-center">
                                            <form method="post" action="{{ route('stores.processDeleteRequest', $store->id) }}">
                                                @csrf
                                                <button type="submit" name="action" value="approve" class="btn btn-success btn-xs mx-1">Approve</button>
                                            </form>
                                            <form method="post" action="{{ route('stores.processDeleteRequest', $store->id) }}">
                                                @csrf
                                                <button type="submit" name="action" value="reject" class="btn btn-danger btn-xs">Reject</button>
                                            </form>
                                        </div>
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
    </div>
</main>

<script>
    let table = new DataTable('#deleteRequestsTable', {
        ordering: false // Disable DataTables' default ordering
    });

</script>

@endsection
