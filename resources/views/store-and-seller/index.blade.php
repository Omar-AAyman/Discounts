@extends('layout')
@section('title' ,'Sellers & Stores')
@section('content')

<main>
    <div class="container mt-n5">
        <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0">Sellers & Stores List</h6>
                    <a href="{{ route('store-and-seller.create') }}" class="btn btn-success mb-3">
                        <i class="fas fa-plus"></i> Add New Store & Seller
                    </a>
                </div>
            </div>

            @if (session('success'))
            <div class="alert alert-success m-3" role="alert">{{ session('success') }}</div>
            @endif
            @if ($errors->has('fail'))
            <div class="alert alert-danger m-3">{{ $errors->first('fail') }}</div>
            @endif

            @if (!isset($sellers) || $sellers->isEmpty())
            <div class="card-body">
                <h4 class="text-center text-muted">No Sellers Found</h4>
            </div>
            @else
            <div class="card-body">
                <div class="table-responsive">
                    <table id="myTable" class="table table-bordered table-hover small-table-text">
                        <thead>
                            <tr style="white-space: nowrap; font-size: 14px;">
                                <th>Store Image</th>
                                <th>Seller Info</th>
                                <th>Store Info</th>
                                <th>Contact Details</th>
                                <th>Created By</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sellers as $seller)
                            @php
                            $store = $seller['store'] ?? null;
                            $sellerData = $seller['seller'] ?? null;
                            $delegate = $store->delegate ?? null;
                            $status = $store->status ?? 'unknown';

                            $statusClass = match($status) {
                            'approved' => 'bg-success',
                            'pending' => 'bg-warning',
                            'rejected' => 'bg-danger',
                            default => 'bg-secondary'
                            };

                            $statusIcon = match($status) {
                            'approved' => 'fas fa-check-circle',
                            'pending' => 'fas fa-hourglass-half',
                            'rejected' => 'fas fa-times-circle',
                            default => 'fas fa-question-circle'
                            };
                            @endphp
                            <tr style="white-space: nowrap; font-size: 14px;">
                                <td>
                                    <img src="{{ $store && $store->store_img ? $store->store_img : asset('images/default-store.png') }}" alt="Store Image" class="img-thumbnail image">
                                </td>
                                <td>
                                    <strong>Name:</strong> {{ $sellerData->first_name ?? 'N/A' }} {{ $sellerData->last_name ?? '' }}<br>
                                    <strong>ID:</strong> {{ $sellerData->id ?? 'N/A' }}<br>
                                    <strong>Type:</strong> <span class="text-primary">{{ ucfirst($sellerData->seller_type_id ?? 'N/A') }}</span><br>
                                    <strong>Created:</strong> <span class="text-primary">{{ $sellerData->created_at ? \Carbon\Carbon::parse($sellerData->created_at)->format('Y-m-d') : 'N/A' }}</span>
                                </td>
                                <td>
                                    <strong>Store:</strong> {{ $store->name ?? 'N/A' }}<br>
                                    <strong>City:</strong> {{ optional($store->countryRelation)->name ?? 'N/A' }}<br>
                                    <strong>Area:</strong> {{ optional($store->cityRelation)->name ?? 'N/A' }}<br>
                                    <strong>Work Hours:</strong> {{ $store->work_hours ?? 'N/A' }}
                                </td>
                                <td>
                                    <strong>Email:</strong> {{ $sellerData->email ?? 'N/A' }}<br>
                                    <strong>Phone:</strong> {{ $sellerData->phone ?? 'N/A' }}<br>
                                    @if(!empty($store->phone_number2))
                                    <strong>Whatsapp:</strong> {{ $store->phone_number2 }}
                                    @endif
                                </td>
                                <td>
                                    @if ($delegate)
                                    <a href="{{ route('users.edit', ['uuid' => $delegate->uuid]) }}" class="text-primary">
                                        {{ $delegate->fullname }}
                                    </a><br>
                                    <strong>ID:</strong> {{ $delegate->id }}<br>
                                    @else
                                    <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge text-white p-2 {{ $statusClass }}">
                                        <i class="{{ $statusIcon }}"></i>
                                        {{ ucfirst($status) }}
                                    </span>
                                    <br>
                                    <span class="badge text-white {{ !empty($sellerData->is_online) ? 'bg-success' : 'bg-secondary' }}">
                                        {{ !empty($sellerData->is_online) ? 'Online' : 'Offline' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('store-and-seller.edit', ['seller' => $sellerData]) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i> Edit Seller
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
    $(document).ready(function() {
        $('#myTable').DataTable({
            ordering: true
            , pageLength: 10
            , responsive: true
            , dom: '<"html5buttons"B>lTfgitp'
            , buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
            , searching: true
            , language: {
                search: "Search:"
                , searchPlaceholder: "Search sellers..."
            }
            , columnDefs: [{
                targets: '_all'
                , searchable: true
            }]
        });
    });

</script>

<style>
    .img-thumbnail {
        border-radius: 8px;
        object-fit: cover;
    }

    .dataTables_length select {
        padding-right: 25px !important;
        min-width: 80px;
    }

    .dataTables_wrapper .dataTables_length {
        margin-bottom: 10px;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        position: relative;
        padding: 0.5em 1em;
        margin-left: 2px;
        line-height: 1.42857143;
        border: 1px solid #ddd;
        background-color: #fff;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background-color: #007bff;
        color: white !important;
        border-color: #007bff;
    }

    .image {
        max-width: 80px;
        max-height: 80px;
        object-fit: cover;
        border-radius: 5px;
    }

</style>

@endsection
