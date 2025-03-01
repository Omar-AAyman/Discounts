@extends('layout')

@section('title', 'Edit City')

@section('content')
<div class="container mt-4">
    <h2>Edit City</h2>

    <form action="{{ route('cities.update', $city->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">City En Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $city->name }}" required>
        </div>
        <div class="mb-3">
            <label for="name_ar" class="form-label">City Ar Name</label>
            <input type="text" name="name_ar" id="name_ar" class="form-control" value="{{ $city->name_ar }}" required>
        </div>
        <button type="submit" class="btn btn-outline-success btn-sm">Update</button>
        <a href="{{ route('cities.index') }}" class="btn btn-outline-secondary btn-sm">Cancel</a>
    </form>
</div>
@endsection
