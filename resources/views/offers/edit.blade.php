@extends('layout')
@section('title', 'Edit Offer')
@section('content')
<main>
    <div class="container mt-5">
        <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

        @if ($errors->has('fail'))
        <div class="alert alert-danger">
            {{ $errors->first('fail') }}
        </div>
        @endif

        <div class="row m-5">
            <div class="col-xl-4">
                <!-- Image Card -->
                <!-- Background Image Card -->
                <div class="card mb-4 mb-xl-0">
                    <div class="card-header">Background Image</div>
                    <div class="card-body text-center">
                        <!-- Display Background Image -->
                        @if (isset($offer->bg_img))
                        <img id="bg-image" width="160" height="160" class="img-account-profile mb-1" src="{{  $offer->bg_img }}" alt="Background Image" />
                        @else
                        <img id="bg-image" width="160" height="160" class="img-account-profile mb-1" src="{{ asset('assets/img/noimg.jpg') }}" alt="No Background Image" />
                        @endif
                        <!-- Upload Background Image -->
                        <form method="POST" action="{{ route('offers.update', $offer->id) }}" enctype="multipart/form-data" id="image-form">
                        @csrf
                        @method('PUT')
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
                                <input class="form-control" name="title" type="text" value="{{ $offer->title }}" required />
                            </div>
                            <div class="col-md-6">
                                <label for="store_id" class="form-label">Store</label>
                                <input class="form-control" name="store_id" type="text" value="{{ $offer->store->name }}" readonly />
                            </div>
                        </div>
                        <div class="row gx-3 mb-3">
                            <div class="col-md-6">
                                <label for="discount_percentage" class="form-label">Discount (%)</label>
                                <input class="form-control" name="discount_percentage" type="number" step="0.01" value="{{ $offer->discount_percentage }}" required />
                            </div>
                            <div class="col-md-6">
                                <label for="price_before_discount" class="form-label">Price Before Discount</label>
                                <input class="form-control" name="price_before_discount" type="number" step="0.01" value="{{ $offer->price_before_discount }}" required />
                            </div>
                        </div>
                        <div class="row gx-3 mb-3">
                            <div class="col-md-6">
                                <label for="is_online" class="form-label">Is Online</label>
                                <input class="form-check-input ml-3" name="is_online" type="checkbox" {{ $offer->is_online ? 'checked' : '' }} />
                            </div>
                        </div>

                        <div class="row gx-3 mb-3">
                            <div class="col-12">
                                <label for="exclusions" class="form-label">Exclusions</label>
                                <textarea class="form-control" name="exclusions">
                                {{ $offer->exclusions }}
                                </textarea>


                            </div>
                        </div>
                        <div class="row gx-3 mb-3">
                            <div class="col-12">
                                <button class="btn btn-primary btn-sm" type="submit">Save Changes</button>
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
