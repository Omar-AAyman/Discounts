@extends('layout')

@section('title', 'Add City')

@section('content')
<div class="container mt-4">
    <h2>Add City</h2>

    <form action="{{ route('cities.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">City En Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="name_ar" class="form-label">City Ar Name</label>
            <input type="text" name="name_ar" id="name_ar" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-outline-success btn-sm">Save</button>
        <a href="{{ route('cities.index') }}" class="btn btn-outline-secondary btn-sm">Cancel</a>
    </form>
</div>
@endsection
