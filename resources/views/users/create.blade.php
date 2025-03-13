@extends('layout')

@section('content')


<main>


    <!-- Main page content-->
    <div class="container mt-n5">


        <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
        <div class="card">
            <div class="card-header">Create new user</div>
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

                <form action="{{ route('users.store') }}" method="POST">
                    @csrf

                    <div class="row gx-3 mb-3">

                        <div class="col-md-6">
                            <label class="small mb-1" for="first_name">First Name </label>
                            <input type="text" name="first_name" id="first_name" class="form-control" value="{{old('first_name')}}" required />
                            @error('first_name')
                            {{$message}}
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="small mb-1" for="last_name">Last Name </label>
                            <input type="text" name="last_name" id="last_name" class="form-control" value="{{old('last_name')}}" required />
                            @error('last_name')
                            {{$message}}
                            @enderror
                        </div>
                    </div>
                    <div class="row gx-3 mb-3">
                        <div class="col-md-6">
                            <label class="small mb-1" for="email">Email </label>
                            <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{old('email')}}" required />
                            @error('email')
                            {{$message}}
                            @enderror
                        </div>



                        <div class="col-md-6">
                            <label class="small mb-1" for="password">Password</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                            @error('password')
                            {{$message}}
                            @enderror
                        </div>
                    </div>
                    <div class="row gx-3 mb-3">
                        <div class="col-md-6">
                            <label class="small mb-1" for="password">Password Confirmation</label>
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">

                        </div>


                        <div class="col-md-6">
                            <label class="small mb-1" for="phone">Phone</label>
                            <input id="phone" type="text" class="form-control" name="phone" required value="{{old('phone')}}">

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
                                <option value="{{ $country->id }}" {{ old('country') == $country->id ? 'selected' : '' }}>
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


                    <div class="row gx-3 mb-3">


                        <div class="col-md-6">
                            <label class="small mb-1" for="phone">Type</label>
                            <select name="type" class="form-control form-control-solid">
                                <option value="">Select a user type </option>
                                <option value="client">Client</option>
                                <option value="delegate">Delegate</option>
                                <option value="customer_support">Customer Support</option>
                            </select>
                        </div>



                        <div class="col-md-6" style="margin-top: 2rem;">
                            <button type="submit" class="btn btn-primary btn-sm">Create</button>
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
        const oldCityId = @json(old('city') ?? null);

        countrySelect.addEventListener('change', function() {
            const countryId = this.value;
            citySelect.innerHTML = '<option value="">Select City</option>'; // Reset cities

            if (countryId) {
                fetch(`/api/cities/${countryId}`)
                    .then(response => response.json())
                    .then(cities => {
                        cities.forEach(city => {
                            const option = new Option(city.name, city.id);
                            option.selected = (city.id == oldCityId);
                            citySelect.add(option);
                        });
                    })
                    .catch(error => console.error('Error fetching cities:', error));
            }
        });

        // Trigger change if country is pre-selected (e.g., form validation failed)
        if (countrySelect.value) {
            countrySelect.dispatchEvent(new Event('change'));
        }
    });

</script>
@endsection
