@extends('layout')
@section('title', 'OnBoarding Slides')

@section('content')

<main>
    <!-- Main page content -->
    <div class="container mt-n5">

        <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

        <div class="card">
            <div class="card-header">List of OnBoarding Slides</div>
            <div class="card-body">

                @if ($onBoardings->isEmpty())
                    <p>No OnBoarding Slides Available.</p>
                @else
                    <div class="mt-3 table-container">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <table id="myTable" class="table small-table-text">
                            <thead>
                                <tr style="white-space: nowrap; font-size: 14px;">
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Subtitle</th>
                                    <th>Text Button</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($onBoardings as $onBoarding)
                                    <tr style="white-space: nowrap; font-size: 14px;">
                                        <td>
                                            @if (isset($onBoarding->image_url))
                                                <img src="{{ $onBoarding->image_url }}" alt="Image" width="100" height="100">
                                            @else
                                                <img src="{{ asset('assets/img/noimg.jpg') }}" alt="No Image" width="100" height="100">
                                            @endif
                                        </td>
                                        <td>{{ $onBoarding->title ?? 'No Title' }}</td>
                                        <td>{{ $onBoarding->subtitle ?? 'No Subtitle' }}</td>
                                        <td>{{ $onBoarding->textbutton ?? 'No Text Button' }}</td>
                                        <td>
                                            <a href="{{ route('onboardings.edit', $onBoarding->id) }}" class="btn btn-primary btn-xs">Edit</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</main>

<script>
    let table = new DataTable('#myTable', {
        ordering: false // Disable DataTables' default ordering
    });
</script>

@endsection
