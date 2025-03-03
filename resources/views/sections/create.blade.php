@extends('layout')
@section('title', 'New Section')

@section('content')
<main>
    <div class="container mt-n5">
        <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
        <div class="card">
            <div class="card-header">Create new section</div>
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
                <form action="{{ route('sections.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row gx-3 mb-3">
                        <div class="col-md-6">
                            <label class="small mb-1" for="name">Name</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{old('name')}}" required />
                            @error('name')
                            <div class="text-danger">{{$message}}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="small mb-1" for="name_ar">Arabic Name</label>
                            <input type="text" name="name_ar" id="name_ar" class="form-control" value="{{old('name_ar')}}" required />
                            @error('name_ar')
                            <div class="text-danger">{{$message}}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row gx-3 mb-3">
                        <div class="col-md-6">
                            <label class="small mb-1" for="img">Image</label>
                            <input type="file" name="img" id="img" class="form-control" accept="image/*" onchange="previewImage(event)" required />
                            @error('img')
                            <div class="text-danger">{{$message}}</div>
                            @enderror
                            <div class="mt-2">
                                <img id="imgPreview" src="#" alt="Image Preview" class="img-thumbnail d-none" width="150" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="small mb-1" for="packages">Packages</label>
                            <select name="package_ids[]" id="packages" class="form-control" multiple required>
                                <option value="">Select packages</option>
                                @foreach($packages as $package)
                                <option value="{{ $package->id }}" {{ in_array($package->id, old('package_ids', [])) ? 'selected' : '' }}>{{ $package->name }}</option>
                                @endforeach
                            </select>
                            @error('package_ids')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row gx-3 mb-3">
                        <div class="col-md-6">
                            <label class="small mb-1" for="type">Type</label>
                            <input type="text" name="type" id="type" class="form-control" value="{{old('type')}}" required />
                            @error('type')
                            <div class="text-danger">{{$message}}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="small mb-1" for="description">Description</label>
                            <textarea id="description" name="description" class="form-control" required>{{old('description')}}</textarea>
                            @error('description')
                            <div class="text-danger">{{$message}}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row gx-3 mb-3">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-sm">Create</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
<script>
    function previewImage(event) {
        const imgPreview = document.getElementById('imgPreview');
        imgPreview.src = URL.createObjectURL(event.target.files[0]);
        imgPreview.classList.remove('d-none');
    }
</script>
@endsection
