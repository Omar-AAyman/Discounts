@extends('layout')
@section('title', 'Create Store & Seller')

@section('content')


<main>


    <!-- Main page content-->
    <div class="container mt-n5">


        <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
        <div class="card">
            <div class="card-header p-3">Add a new Store & Seller</div>
            <div class="card-body p-4">


                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('store-and-seller.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="card-header bg-light p-2 mb-4">
                        <h5 class="mb-0">Seller Information</h5>
                    </div>

                    <div class="row gx-4 mb-4">

                        <div class="col-md-6">
                            <label class="small mb-2" for="seller_first_name">Seller's First Name <span style="color: red;">*</span></label>
                            <input type="text" name="seller_first_name" id="seller_first_name" class="form-control py-2" value="{{old('seller_first_name')}}" required />
                            @error('seller_first_name')
                            <span class="text-danger small mt-1">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="small mb-2" for="seller_last_name">Seller's Last Name <span style="color: red;">*</span></label>
                            <input type="text" name="seller_last_name" id="seller_last_name" class="form-control py-2" value="{{old('seller_last_name')}}" required />
                            @error('seller_last_name')
                            <span class="text-danger small mt-1">{{$message}}</span>
                            @enderror
                        </div>

                        <div class="col-md-6 mt-2">
                            <label class="small mb-2" for="seller_type">Seller Type <span style="color: red;">*</span></label>
                            <select name="seller_type" id="seller_type" class="form-control form-select" required>
                                <option value="">Select a seller type</option>
                                @foreach($sellerTypes as $type)
                                <option value="{{$type->id}}" {{ old('seller_type') == $type->id ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $type->name)) }}: {{ $type->en_description }}
                                </option>
                                @endforeach
                            </select>
                            @error('seller_type')
                            <span class="text-danger small">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 mt-2">
                            <label class="small mb-2" for="phone_number1">Email <span style="color: red;">*</span></label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror py-2" name="email" value="{{ old('email') }}" required autocomplete="email">


                        </div>
                    </div>
                    <div class="row gx-4 mb-4">
                        <div class="col-md-6">
                            <label class="small mb-2" for="phone_number1">Phone Number<span style="color: red;">*</span></label>
                            <input type="text" name="phone_number1" id="phone_number1" class="form-control py-2" value="{{old('phone_number1')}}" required />


                        </div>
                        <div class="col-md-6">
                            <label class="small mb-2" for="phone_number2">Whatsapp Number</label>
                            <input type="text" name="phone_number2" id="phone_number2" class="form-control py-2" value="{{old('phone_number2')}}" />

                        </div>


                    </div>

                    <div class="row gx-4 mb-4">
                        <div class="col-md-6">
                            <label class="small mb-2" for="password">Password <span style="color: red;">*</span></label>
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror py-2" required />
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="small mb-2" for="password_confirmation">Confirm Password <span style="color: red;">*</span></label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control py-2" required />
                        </div>
                    </div>


                    <div class="card-header bg-light p-2 mb-4">
                        <h5 class="mb-0">Store Information</h5>
                    </div>

                    <div class="row gx-4 mb-4">
                        <div class="col-md-6">
                            <label class="small mb-2" for="store_name">Store's Name <span style="color: red;">*</span></label>
                            <input type="text" name="store_name" id="store_name" class="form-control py-2" value="{{old('store_name')}}" required />
                            @error('store_name')
                            {{$message}}
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="small mb-2" for="section_id">Section <span style="color: red;">*</span></label>
                            <select name="section_id" id="section_id" class="form-control form-control-solid" required>
                                <option value="">Select a section</option>
                                @foreach($sections as $section)
                                <option value="{{$section->id}}">{{$section->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- New Country/City dropdown row -->
                    <div class="row gx-4 mb-4">
                        <!-- Country dropdown -->
                        <div class="col-md-6">
                            <label class="small mb-2" for="country_id">City <span style="color: red;">*</span></label>
                            <select name="country_id" id="country_id" class="form-control form-select" required>
                                <option value="">Select City</option>
                                @foreach($countries as $country)
                                <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>
                                    {{ $country->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- City dropdown -->
                        <div class="col-md-6">
                            <label class="small mb-2" for="city_id">Area <span style="color: red;">*</span></label>
                            <select name="city_id" id="city_id" class="form-control form-select" required>
                                <option value="">Select Area</option>
                            </select>
                            @error('city_id')
                            <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row gx-4 mb-4">
                        <div class="col-md-4">
                            <label class="small mb-2" for="licensed_operator_number">Licensed operator number</label>
                            <input type="text" name="licensed_operator_number" id="licensed_operator_number" class="form-control py-2" value="{{old('licensed_operator_number')}}" />

                        </div>
                        <div class="col-md-4">
                            <label class="small mb-2" for="sector_representative">Sector representative <span style="color: red;">*</span></label>
                            <input type="text" name="sector_representative" id="sector_representative" class="form-control py-2" value="{{old('sector_representative')}}" required />


                        </div>
                        <div class="col-md-4">
                            <label class="small mb-2" for="location">Location Url<span style="color: red;">*</span></label>
                            <input id="location" name="location" class="form-control py-2" value="{{old('location')}}" required />
                            @error('location')
                            {{$message}}
                            @enderror
                        </div>
                    </div>









                    <div class="row gx-4 mb-4">
                        <div class="col-md-6">
                            <label class="small mb-2">Working days <span style="color: red;">*</span></label>
                            <div class="border rounded p-2">
                                <div class="d-flex flex-wrap gap-3">
                                    @php
                                    $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
                                    $oldDays = is_array(old('working_days')) ? old('working_days') : (old('working_days') ? json_decode(old('working_days'), true) : []);
                                    @endphp

                                    @foreach($days as $day)
                                    <div class="form-check mx-1 ">
                                        <input class="form-check-input " type="checkbox" name="working_days[]" value="{{ $day }}" id="day_{{ $day }}" {{ in_array($day, $oldDays) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="day_{{ $day }}">
                                            {{ ucfirst($day) }}
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @error('working_days')
                            <span class="text-danger small mt-1">{{$message}}</span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <div class="d-flex gap-3">
                                <div class="flex-grow-1 me-3">
                                    <label class="small" for="working_hours_from">Working From</label>
                                    <input type="time" name="working_hours_from" id="working_hours_from" class="form-control py-2" value="{{old('working_hours_from')}}" format="12" required />
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <label class="small" for="working_hours_to">Working To</label>
                                    <input type="time" name="working_hours_to" id="working_hours_to" class="form-control py-2" value="{{old('working_hours_to')}}" format="12" required />
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="row gx-4 mb-4">


                        <div class="col-md-6">
                            <label class="small mb-2" for="facebook">Facebook</label>
                            <input type="text" name="facebook" id="facebook" class="form-control py-2" value="{{old('facebook')}}" />


                        </div>
                        <div class="col-md-6">
                            <label class="small mb-2" for="instagram">Instagram</label>
                            <input type="text" name="instagram" id="instagram" class="form-control py-2" value="{{old('instagram')}}" />


                        </div>


                    </div>



                    <div class="row gx-4 mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="contract_img">Contract Image</label>
                                <div class="input-group">
                                    <input type="file" class="form-control py-2" name="contract_img" id="contract_img" accept="image/*">
                                </div>
                                @error('contract_img')
                                <span class="text-danger small">{{$message}}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="store_img">Sector/Store Image</label>
                                <div class="input-group">
                                    <input type="file" class="form-control py-2" name="store_img" id="store_img" accept="image/*">
                                </div>
                                @error('store_img')
                                <span class="text-danger small">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary px-4 py-2">Create Seller</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</main>



<script>
document.addEventListener('DOMContentLoaded', function() {
    const countrySelect = document.getElementById('country_id');
    const citySelect = document.getElementById('city_id');
    const oldCityId = @json(old('city_id') ?? null);

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
