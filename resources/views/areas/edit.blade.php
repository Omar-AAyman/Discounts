@extends('layout')

@section('title', 'Edit Area')

@section('content')
<div class="container mt-4">
    <h2>Edit Area</h2>

    <form action="{{ route('areas.update', $area->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Area En Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $area->name }}" required>
        </div>
        <div class="mb-3">
            <label for="name_ar" class="form-label">Area Ar Name</label>
            <input type="text" name="name_ar" id="name_ar" class="form-control" value="{{ $area->name_ar }}" required>
        </div>
        <div class="mb-3">
            <label for="country_id" class="form-label">City</label>
            <select name="country_id" id="country_id" class="form-control" required>
                @foreach ($cities as $city)
                    <option value="{{ $city->id }}" {{ $area->country_id == $city->id ? 'selected' : '' }}>
                        {{ $city->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-outline-success btn-sm">Update</button>
        <a href="{{ route('areas.index') }}" class="btn btn-outline-secondary btn-sm">Cancel</a>
    </form>
</div>
@endsection
