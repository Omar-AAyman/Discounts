@extends('layout')
@section('title', 'All Offers')
@section('content')

<main>
    <!-- Main page content -->
    <div class="container mt-n5">

        <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

        <div class="card">
            <div class="card-header">List of Offers</div>
            <div class="card-body">

                @if ($offers->isEmpty())
                    <p>No Offers Available.</p>
                @else
                    <div class="mt-3 table-container">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <table id="myTable" class="table small-table-text">
                            <thead>
                                <tr style="white-space: nowrap; font-size: 14px;">
                                    <th>Title</th>
                                    <th>Store</th>
                                    <th>Discount (%)</th>
                                    <th>Price Before Discount</th>
                                    <th>Background Image</th>
                                    <th>Is Online</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($offers as $offer)
                                    <tr style="white-space: nowrap; font-size: 14px;">
                                        <td>{{ $offer->title ?? 'No Title' }}</td>
                                        <td>{{ $offer->store->name ?? 'No Store' }}</td>
                                        <td>{{ $offer->discount_percentage ?? 'N/A' }}</td>
                                        <td>{{ $offer->price_before_discount ?? 'N/A' }}</td>
                                        <td>
                                            @if (isset($offer->bg_img))
                                                <img src="{{$offer->bg_img }}" alt="Background Image" width="100" height="100">
                                            @else
                                                <img src="{{ asset('assets/img/noimg.jpg') }}" alt="No Background Image" width="100" height="100">
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge {{ $offer->is_online ? 'badge-green' : 'badge-red' }}">
                                                {{ $offer->is_online ? 'Online' : 'Offline' }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('offers.edit', $offer->id) }}" class="btn btn-primary btn">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
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
