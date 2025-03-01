@extends('layout')

@section('title', 'Areas')

@section('content')
<div class="container mt-4">
    <h2>Areas</h2>
    <a href="{{ route('areas.create') }}" class="btn btn-primary mb-3">
        <i class="fas fa-plus"> </i> Add New Area
    </a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-hover text-center">
        <thead>
            <tr>
                <th>ID</th>
                <th>En Name</th>
                <th>Ar Name</th>
                <th>City</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($areas as $area)
            <tr>
                <td>{{ $area->id }}</td>
                <td>{{ $area->name }}</td>
                <td>{{ $area->name_ar }}</td>
                <td>{{ $area->country->name }}</td>
                <td>
                    <a href="{{ route('areas.edit', $area->id) }}" class="btn btn-outline-warning btn-sm">
                        <i class="fas fa-edit"> </i>
                    </a>
                    <form action="{{ route('areas.destroy', $area->id) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Delete this area?')">
                            <i class="fas fa-trash"> </i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Font Awesome (Ensure you have it in your layout or add this) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection
