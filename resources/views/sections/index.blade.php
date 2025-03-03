@extends('layout')
@section('title', 'All Sections')

@section('content')


<main>


    <!-- Main page content-->
    <div class="container mt-n5">


        <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

        <div class="card">
            <div class="card-header">Sections List

            </div>
            @if (session('success'))

            <div class="alert alert-success m-3" role="alert">{{ session('success') }}</div>
            @endif
            @if ($errors->has('fail'))
            <div class="alert alert-danger m-3">
                {{ $errors->first('fail') }}
            </div>
            @endif


            @if ($sections->isEmpty())
            <div class="card-body">

                <h4>No sections</h4>
            </div>
            @else
            <div class="card-body">
                <table id="myTable" class="table small-table-text text-center">
                    <thead>
                        <tr style="white-space: nowrap; font-size: 14px;">
                            <th>Section Image</th>
                            <th>English Name</th>
                            <th>Arabic Name</th>
                            <th>Type</th>
                            <th>Belongs to packages</th>
                            <th>Is Online</th>
                            <th>Actions</th>
                            <th></th>


                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sections as $section )
                        <tr style="white-space: nowrap; font-size: 14px;">
                            <td>
                                <img src="{{ $section && $section->img ? $section->img : asset('images/default-section.png') }}" alt="Section Image" class="img-thumbnail image">
                            </td>
                            <td class=" text-black"><b>{{ $section->name }}</b></td>
                            <td class=" text-black"><b>{{ $section->name_ar }}</b></td>
                            <td>{{$section->type}}</td>
                            <td>
                                @if ($section->packages->isNotEmpty())
                                <ul>
                                    @foreach ($section->packages as $package)
                                    <li>{{ $package->name }}</li>
                                    @endforeach
                                </ul>
                                @else
                                No packages
                                @endif
                            </td>


                            <td>
                                <span class="badge {{ $section->is_online ? 'badge-green' : 'badge-red' }}">
                                    {{ $section->is_online ? 'Online' : 'Offline' }}
                                </span>

                            </td>

                            <td>
                                <a class="btn btn-primary btn-sm" href="{{route('sections.edit' , ['uuid'=>$section['uuid'] ])}}">
                                    Edit
                                </a>
                                <form action="{{ route('sections.destroy', ['uuid' => $section['uuid']]) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this section?');">Delete</button>
                                </form>
                            </td>
                            <td>
                                <a class="btn btn-success btn-sm" href="{{route('sections.showStores' , ['uuid'=>$section['uuid'] ])}}">
                                    Show stores points
                                </a>
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
            @endif


        </div>
    </div>


</main>





<script>
    let table = new DataTable('#myTable', {
        ordering: false // Disable DataTables' default ordering
    });

</script>


@endsection
