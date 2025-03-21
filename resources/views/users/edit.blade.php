@extends('layout')

@section('content')


<main>


    <!-- Main page content-->
    <div class="container mt-n5">


        <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
        <div class="card">
            <div class="card-header">Edit user</div>
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

                <form action="{{ route('users.update',$user->uuid) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row gx-3 mb-3">

                        <div class="col-md-6">
                            <label class="small mb-1" for="email">Email </label>
                            <input class="form-control @error('email') is-invalid @enderror" value="{{$user->email}}" readonly />


                        </div>

                        <div class="col-md-6">
                            <label class="small mb-1" for="first_name">First_Name </label>
                            <input type="text" name="first_name" id="first_name" class="form-control" value="{{$user->first_name}}" required />
                            @error('name')
                            {{$message}}
                            @enderror
                        </div>
                    </div>

                    <div class="row gx-3 mb-3">
                        <div class="col-md-6">
                            <label class="small mb-1" for="last_name">last_Name </label>
                            <input type="text" name="last_name" id="last_name" class="form-control" value="{{$user->last_name}}" required />
                            @error('name')
                            {{$message}}
                            @enderror
                        </div>





                        <div class="col-md-6">
                            <label class="small mb-1" for="phone">Phone</label>
                            <input id="phone" value="{{$user->phone}}" type="string" class="form-control" name="phone" required>

                            @error('phone')
                            {{$message}}
                            @enderror
                        </div>
                    </div>
                    <div class="row gx-3 mb-3">


                        <div class="col-md-6">
                            <label class="small mb-1" for="phone2">Phone 2</label>
                            <input id="phone2" value="{{$user->phone2}}" type="string" class="form-control" name="phone2">

                            @error('phone2')
                            {{$message}}
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="small mb-1" for="is_online" style="margin-top: 40px;">Is online</label>
                            <input id="is_online" type="checkbox" name="is_online" {{$user->is_online? 'checked':''}}>

                        </div>
                    </div>



                    <div class="row gx-3 mb-3">


                        <div class="col-md-6">
                            <label class="small mb-1" for="facebook">Facebook</label>
                            <input id="facebook" value="{{$user->facebook}}" type="string" class="form-control" name="facebook">

                        </div>

                        <div class="col-md-6">
                            <label class="small mb-1" for="instagram">Instagram</label>
                            <input id="instagram" value="{{$user->instagram}}" type="string" class="form-control" name="instagram">

                        </div>
                    </div>


                    <!-- New Country/City dropdown row -->
                    <div class="row gx-4 mb-4">
                        <!-- Country dropdown -->
                        <div class="col-md-6">
                            <label class="small mb-2" for="country">City <span style="color: red;">*</span></label>
                            <select name="country" id="country" class="form-control form-select" required>
                                <option value="">Select City</option>
                                @foreach($countries as $country)
                                <option value="{{ $country->id }}" {{ old('country',$user->country) == $country->id ? 'selected' : '' }}>
                                    {{ $country->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- City dropdown -->
                        <div class="col-md-6">
                            <label class="small mb-2" for="city">Area <span style="color: red;">*</span></label>
                            <select name="city" id="city" class="form-control form-select" required>
                                <option value="">Select Area</option>
                            </select>
                            @error('city')
                            <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row gx-3 my-3">
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
    document.addEventListener('DOMContentLoaded', function() {
        const countrySelect = document.getElementById('country');
        const citySelect = document.getElementById('city');
        const oldCityId = @json(old('city', $user -> city));

        function fetchCities(countryId, selectedCityId = null) {
            citySelect.innerHTML = '<option value="">Select City</option>'; // Reset cities
            if (countryId) {
                fetch(`/api/cities/${countryId}`)
                    .then(response => response.json())
                    .then(cities => {
                        cities.forEach(city => {
                            const option = new Option(city.name, city.id, false, city.id == selectedCityId);
                            citySelect.add(option);
                        });
                    });
            }
        }

        // Fetch cities on country change
        countrySelect.addEventListener('change', function() {
            fetchCities(this.value);
        });

        // Load cities for preselected country
        if (countrySelect.value) {
            fetchCities(countrySelect.value, oldCityId);
        }
    });

</script>
@endsection
