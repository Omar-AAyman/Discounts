@extends('delegates.layout-delegate')
@section('title' ,'Delegate Sellers')
@section('content')


<main>


    <!-- Main page content-->
    <div class="container mt-n5">


        <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0">Sellers List</h6>
                    <a href="{{ route('delegates.createSeller') }}" class="btn btn-primary btn-sm">Add New Seller</a>
                </div>
            </div>
            @if (session('success'))
            <div class="alert alert-success m-3" role="alert">{{ session('success') }}</div>
            @endif
            @if ($errors->has('fail'))
            <div class="alert alert-danger m-3">
                {{ $errors->first('fail') }}
            </div>
            @endif

            @if (empty($sellers))
            <div class="card-body">
                <h4>No Sellers Found</h4>
            </div>
            @else
            <div class="card-body">
                <table id="myTable" class="table table-bordered table-hover small-table-text">
                    <thead>
                        <tr style="white-space: nowrap; font-size: 14px;">
                            <th>Seller Info</th>
                            <th>Store Info</th>
                            <th>Contact Details</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sellers as $seller)
                        <tr style="white-space: nowrap; font-size: 14px;">
                            <td>
                                <strong>Name:</strong> {{ $seller['seller']->first_name }} {{ $seller['seller']->last_name }}<br>
                                <strong>ID:</strong> {{ $seller['seller']->id }}</span><br>
                                <strong>Type:</strong> <span class="text-primary">{{ ucfirst($seller['seller']->seller_type_id) }}</span><br>
                                <strong>Created:</strong> <span class="text-primary">{{ \Carbon\Carbon::parse($seller['seller']->created_at)->format('Y-m-d') }}</span>
                            </td>
                            <td>
                                <strong>Store:</strong> {{ $seller['store']->name ?? 'N/A' }}<br>
                                <strong>City:</strong> {{ $seller['store']->cityRelation->name ?? 'N/A' }}<br>
                                <strong>Country:</strong> {{ $seller['store']->countryRelation->name ?? 'N/A' }}<br>
                                <strong>Work Hours:</strong> {{ $seller['store']->work_hours ?? 'N/A' }}
                            </td>
                            <td>
                                <strong>Email:</strong> {{ $seller['seller']->email }}<br>
                                <strong>Phone:</strong> {{ $seller['seller']->phone }}<br>
                                @if($seller['store']->phone_number2)
                                <strong>Whatsapp:</strong> {{ $seller['store']->phone_number2 }}
                                @endif
                            </td>
                            <td>
                                <span class="badge text-white {{ $seller['store']->status === 'approved' ? 'bg-success' : 'bg-warning' }}">
                                    {{ ucfirst($seller['store']->status) }}
                                </span>
                                <br>
                                <span class="badge text-white {{ $seller['seller']->is_online ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $seller['seller']->is_online ? 'Online' : 'Offline' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('delegates.editSeller', ['seller' => $seller['seller']]) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i> Edit Seller
                                </a>

                                @if($seller['store']->status !== 'delete_requested')
                                <form action="{{ route('delegates.requestDelete', ['store' => $seller['store']->id]) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to request deletion for this store?');">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i> Request Delete
                                    </button>
                                </form>
                                @else
                                <span class="badge bg-warning p-2 text-white"><i class="fas fa-clock"></i>Deletion Requested</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
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
            , search: {
                return: true
            , }
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
        max-width: 80px;
        object-fit: cover;
        /* Ensures the image fills the area without stretching */
        border-radius: 5px;
        /* Optional: adds slightly rounded corners */
    }

</style>
@endsection
