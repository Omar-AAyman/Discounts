@extends('layout')

@section('title', 'cities')

@section('content')
<div class="container mt-4">
    <h2>Cities</h2>
    <a href="{{ route('cities.create') }}" class="btn btn-primary mb-3">
        <i class="fas fa-plus"> </i> Add New City
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
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cities as $city)
            <tr>
                <td>{{ $city->id }}</td>
                <td>{{ $city->name }}</td>
                <td>{{ $city->name_ar }}</td>
                <td>
                    <a href="{{ route('cities.edit', $city->id) }}" class="btn btn-outline-warning btn-sm">
                        <i class="fas fa-edit"> </i>
                    </a>
                    <form action="{{ route('cities.destroy', $city->id) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Delete this city?')">
                            <i class="fas fa-trash"> </i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
