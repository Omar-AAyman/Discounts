@extends('layout')
@section('title', 'New Offer')
@section('content')
<main>
    <div class="container mt-5">
        <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="row m-5">
            <div class="col-xl-4">
                <!-- Image Card -->
                {{-- <div class="card mb-4 mb-xl-0">
                    <div class="card-header">Offer Image</div>
                    <div class="card-body text-center">
                        <!-- Display Default Image -->
                        <img id="offer-image" width="160" height="160" class="img-account-profile mb-1" src="{{ asset('assets/img/noimg.jpg') }}" alt="Offer Image" />
                <!-- Upload Image -->
                <form method="POST" action="{{ route('offers.store') }}" enctype="multipart/form-data" id="offer-form">
                    @csrf
                    <label for="img" class="btn btn-primary btn-sm mt-2">
                        Upload Image
                    </label>
                    <input style="display: none;" type="file" name="img" id="img" class="form-control-file" onchange="updateImagePreview(event, 'offer-image');">
            </div>
        </div> --}}
        <!-- Background Image Card -->
        <div class="card mb-4 mb-xl-0 mt-4">
            <div class="card-header">Background Image</div>
            <div class="card-body text-center">
                <!-- Display Default Background Image -->
                <img id="bg-image" width="160" height="160" class="img-account-profile mb-1" src="{{ asset('assets/img/noimg.jpg') }}" alt="Background Image" />
                <!-- Upload Background Image -->
                <form method="POST" action="{{ route('offers.store') }}" enctype="multipart/form-data" id="offer-form">
                    @csrf
                    <label for="bg_img" class="btn btn-primary btn-sm mt-2">
                        Upload Background Image
                    </label>
                    <input style="display: none;" type="file" name="bg_img" id="bg_img" class="form-control-file" onchange="updateImagePreview(event, 'bg-image');">
            </div>
        </div>
    </div>
    <div class="col-xl-8">
        <!-- Offer Details Card -->
        <div class="card mb-4">
            <div class="card-header">Offer Details</div>
            <div class="card-body">
                <!-- Form Fields -->
                <div class="row gx-3 mb-3">
                    <div class="col-md-6">
                        <label for="title" class="form-label">Title</label>
                        <input class="form-control" name="title" type="text" value="{{ old('title') }}" required />
                    </div>
                    <div class="col-md-6">
                        <label for="store_id" class="form-label">Store</label>
                        <select name="store_id" id="store_id" class="form-control form-control-solid" required>
                            <option value="">Select a store </option>
                            @foreach($stores as $store)
                            <option value="{{$store->id}}">{{$store->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row gx-3 mb-3">
                    <div class="col-md-6">
                        <label for="discount_percentage" class="form-label">Discount (%)</label>
                        <input class="form-control" name="discount_percentage" type="number" step="0.01" value="{{ old('discount_percentage') }}" required />
                    </div>
                    <div class="col-md-6">
                        <label for="price_before_discount" class="form-label">Price Before Discount</label>
                        <input class="form-control" name="price_before_discount" type="number" step="0.01" value="{{ old('price_before_discount') }}" required />
                    </div>
                </div>

                <div class="row gx-3 mb-3">
                    <div class="col-12">
                        <label for="exclusions" class="form-label">Exclusions</label>
                        <textarea class="form-control" name="exclusions">
                        {{ old('exclusions') }}
                        </textarea>


                    </div>
                </div>
                <div class="row gx-3 mb-3">
                    <div class="col-12">
                        <button class="btn btn-primary btn-sm" type="submit">Create Offer</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
    </div>
    </div>
</main>

<script>
    function updateImagePreview(event, targetId) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById(targetId);
            output.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    }

</script>
@endsection
