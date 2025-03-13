@extends('layout')

@section('title', 'Product Invoices')

@section('content')
<main>
    <div class="container mt-n5">
        <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0">Product Invoices</h6>
            </div>

            <div class="card-body">
                <form method="GET" action="{{ route('invoices.products') }}">
                    <div class="row mb-3">
                        <div class="col-md-2 mt-2">
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-2 mt-2">
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-2 mt-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                        </div>
                        <div class="col-md-2 mt-2">
                            <a href="{{ request()->url() }}" class="btn btn-secondary w-100">
                                <i class="fas fa-times"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table id="invoiceTable" class="table table-bordered table-hover small-table-text text-center">
                        <thead>
                            <tr>
                                <th>Invoice ID</th>
                                <th>Client Name</th>
                                <th>Amount</th>
                                <th>Amount After Discount</th>
                                <th>Store Name</th>
                                <th>Status</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($invoices as $invoice)
                            <tr>
                                <td>#{{ $invoice->id }}</td>
                                <td>{{ $invoice->user->fullname }}</td>
                                <td>{{ $invoice->amount }} ₪</td>
                                <td>{{ $invoice->amount_after_discount }} ₪</td>
                                <td>{{ $invoice->store->name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{ $invoice->status == 'paid' ? 'success' : 'warning' }} text-white px-2">
                                        {{ ucfirst($invoice->status) }}
                                    </span>
                                </td>
                                <td>{{ $invoice->created_at->format('Y-m-d') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    $(document).ready(function() {
        $('#invoiceTable').DataTable({
            ordering: true
            , pageLength: 10
            , responsive: true
            , searching: true
            , language: {
                search: "Search:"
                , searchPlaceholder: "Search invoices..."
            }
        });
    });

</script>
@endsection
