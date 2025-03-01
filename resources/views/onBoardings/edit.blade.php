@extends('layout')
@section('title', 'Edit Slide')
@section('content')

<main>
    <!-- Main page content -->
    <div class="container mt-n5">

        <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
        <div class="card">
            <div class="card-header">Edit OnBoarding Slide</div>
            <div class="card-body">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('onboardings.update', $onBoarding->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row gx-3 mb-3">
                        <!-- Title -->
                        <div class="col-md-6">
                            <label class="small mb-1" for="title">Title</label>
                            <input type="text" name="title" id="title" class="form-control" value="{{ $onBoarding->title }}" required />
                            @error('title')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Subtitle -->
                        <div class="col-md-6">
                            <label class="small mb-1" for="subtitle">Subtitle</label>
                            <input type="text" name="subtitle" id="subtitle" class="form-control" value="{{ $onBoarding->subtitle }}" required />
                            @error('subtitle')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row gx-3 mb-3">
                        <!-- Image Upload -->
                        <div class="col-md-6">
                            <label class="small mb-1" for="image_url">Upload Image</label>
                            <input type="file" name="image_url" id="image_url" class="form-control" accept="image/*" required onchange="previewImage(event)" />
                            <img id="image_preview" src="#" alt="Image Preview" style="display:none; margin-top:10px; max-width:100%;" />
                            @error('image')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Text Button -->
                        <div class="col-md-6">
                            <label class="small mb-1" for="textbutton">Text Button</label>
                            <input type="text" name="textbutton" id="textbutton" class="form-control" value="{{ $onBoarding->textbutton }}" required />
                            @error('textbutton')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row gx-3 mb-3">
                        <!-- Slide ID (Readonly) -->
                        <div class="col-md-6">
                            <label class="small mb-1" for="slide_id">Slide ID (Readonly)</label>
                            <input type="number" name="slide_id" id="slide_id" class="form-control" value="{{ $onBoarding->slide_id }}" readonly />
                        </div>

                        <!-- Order (Readonly) -->
                        <div class="col-md-6">
                            <label class="small mb-1" for="order">Order (Readonly)</label>
                            <input type="number" name="order" id="order" class="form-control" value="{{ $onBoarding->order }}" readonly />
                        </div>
                    </div>

                    <div class="row gx-3 mb-3" style="margin-top: 40px;">
                        <!-- Update Button -->
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary btn-sm">Update</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</main>

<script>
    function previewImage(event) {
        const imagePreview = document.getElementById('image_preview');
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            imagePreview.src = '#';
            imagePreview.style.display = 'none';
        }
    }
</script>

@endsection
