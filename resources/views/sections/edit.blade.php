@extends('layout')
@section('title', 'Edit Section')

@section('content')

    <main>

        <!-- Main page content-->
        <div class="container mt-n5">

                    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
                    <div class="card">
                    <div class="card-header">Edit section</div>
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

                    <form action="{{ route('sections.update',$section->uuid) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row gx-3 mb-3">

                        <div class="col-md-6">
                        <label class="small mb-1" for="name">Name </label>
                        <input type="text" name="name" id="name" class="form-control" value="{{$section->name}}" required/>
                        @error('name')
                                {{$message}}
                        @enderror
                        </div>

                        <div class="col-md-6">
                        <label class="small mb-1" for="name_ar">Name (Arabic)</label>
                        <input type="text" name="name_ar" id="name_ar" class="form-control" value="{{$section->name_ar ?? ''}}" required/>
                        @error('name_ar')
                                {{$message}}
                        @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="small mb-1" for="packages">Packages</label>
                            <select name="package_ids[]" id="packages" class="form-control form-control-solid" multiple required>
                                @foreach($packages as $package)
                                    <option value="{{ $package->id }}" {{ in_array($package->id, $section->packages->pluck('id')->toArray()) ? 'selected' : '' }}>
                                        {{ $package->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                <div class="row gx-3 mb-3">
                <div class="col-md-6">
                        <label class="small mb-1" for="type">Type</label>
                        <input type="text" name="type" id="type" class="form-control" value="{{$section->type}}" required/>
                        @error('type')
                                {{$message}}
                        @enderror
                </div>
                        <div class="col-md-6">
                        <label class="small mb-1" for="description">Description </label>
                        <textarea id="description"  name="description" class="form-control" required>{{$section->description}}</textarea>
                                   @error('description')
                                {{$message}}
                        @enderror
                        </div>
                        </div>

                        <div class="row gx-3 mb-3">
                            <div class="col-md-6">
                                <label class="small mb-1" for="img">Image</label>
                                <input type="file" name="img" id="img" class="form-control" accept="image/*" onchange="previewImage(event)"/>
                                @error('img')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                <br>
                                <img id="img_preview" src="{{$section->img ?? ''}}" alt="Image Preview" style="max-width: 100px; display: {{$section->img ? 'block' : 'none'}};"/>
                            </div>
                        </div>

                        <div class="row gx-3 mb-3" style="margin-top: 40px;">
                        <div class="col-md-6">
                        <label class="small mb-1" for="is_online">Is online</label>
                        <input id="is_online" type="checkbox"  name="is_online" {{$section->is_online? 'checked':''}}>
                        </div>
                        <div class="col-md-6">
                        <button type="submit" class="btn btn-primary btn-sm">Update</button>
                    </div></div>
                    </form>

            </div>
        </div>
        </div>
    </main>

    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function () {
                const output = document.getElementById('img_preview');
                output.src = reader.result;
                output.style.display = 'block';
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>

@endsection
