@extends('layout')
@section('title', 'Edit Seller')

@section('content')
<main>
    <!-- Main page content-->
    <div class="container mt-n5">
        <div class="card">
            {{-- <div class="card-header p-3">Edit Seller</div> --}}
            <div class="card-header p-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Edit Seller</h5>
                <span class="badge text-white p-2
                    @if($seller->store->status == 'approved') bg-success
                    @elseif($seller->store->status == 'pending') bg-warning
                    @else bg-danger
                    @endif">
                    <i class="
                        @if($seller->store->status == 'approved') fas fa-check-circle
                        @elseif($seller->store->status == 'pending') fas fa-times-circle
                        @else fas fa-hourglass-half
                        @endif"></i>
                    {{ ucfirst($seller->store->status) }}
                </span>
            </div>
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

                <form action="{{ route('store-and-seller.update', $seller) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="card-header bg-light p-2 mb-4">
                        <h5 class="mb-0">Seller Information</h5>
                    </div>

                    <div class="row gx-4 mb-4">
                        <div class="col-md-6">
                            <label class="small mb-2" for="seller_first_name">Seller's First Name <span style="color: red;">*</span></label>
                            <input type="text" name="seller_first_name" id="seller_first_name" class="form-control py-2" value="{{ old('seller_first_name', $seller->first_name) }}" required />
                            @error('seller_first_name')
                            <span class="text-danger small mt-1">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="small mb-2" for="seller_last_name">Seller's Last Name <span style="color: red;">*</span></label>
                            <input type="text" name="seller_last_name" id="seller_last_name" class="form-control py-2" value="{{ old('seller_last_name', $seller->last_name) }}" required />
                            @error('seller_last_name')
                            <span class="text-danger small mt-1">{{$message}}</span>
                            @enderror
                        </div>

                        <div class="col-md-6 mt-2">
                            <label class="small mb-2" for="seller_type">Seller Type <span style="color: red;">*</span></label>
                            <select name="seller_type" id="seller_type" class="form-control form-select" required>
                                <option value="">Select a seller type</option>
                                @foreach($sellerTypes as $type)
                                <option value="{{$type->id}}" {{ old('seller_type', $seller->seller_type_id) == $type->id ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $type->name)) }}: {{ $type->en_description }}
                                </option>
                                @endforeach
                            </select>
                            @error('seller_type')
                            <span class="text-danger small">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 mt-2">
                            <label class="small mb-2" for="email">Email <span style="color: red;">*</span></label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror py-2" name="email" value="{{ old('email', $seller->email) }}" required autocomplete="email">
                        </div>
                    </div>

                    <div class="row gx-4 mb-4">
                        <div class="col-md-6">
                            <label class="small mb-2" for="phone_number1">Phone Number<span style="color: red;">*</span></label>
                            <input type="text" name="phone_number1" id="phone_number1" class="form-control py-2" value="{{ old('phone_number1', $seller->phone) }}" required />
                        </div>
                        <div class="col-md-6">
                            <label class="small mb-2" for="phone_number2">Whatsapp Number</label>
                            <input type="text" name="phone_number2" id="phone_number2" class="form-control py-2" value="{{ old('phone_number2', $seller->phone2) }}" />
                        </div>
                    </div>

                    <div class="card-header bg-light p-2 mb-4">
                        <h5 class="mb-0">Store Information</h5>
                    </div>

                    <div class="row gx-4 mb-4">
                        <div class="col-md-6">
                            <label class="small mb-2" for="store_name">Store's Name <span style="color: red;">*</span></label>
                            <input type="text" name="store_name" id="store_name" class="form-control py-2" value="{{ old('store_name', $seller->store->name) }}" required />
                            @error('store_name')
                            {{$message}}
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="small mb-2" for="section_id">Section <span style="color: red;">*</span></label>
                            <select name="section_id" id="section_id" class="form-control form-control-solid" required>
                                <option value="">Select a section</option>
                                @foreach($sections as $section)
                                <option value="{{$section->id}}" {{ old('section_id', $seller->store->section_id) == $section->id ? 'selected' : '' }}>
                                    {{$section->name}}
                                </option>
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
                                <option value="{{ $country->id }}" {{ old('country_id',$seller->store->country) == $country->id ? 'selected' : '' }}>
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
                            <input type="text" name="licensed_operator_number" id="licensed_operator_number" class="form-control py-2" value="{{ old('licensed_operator_number', $seller->store->licensed_operator_number) }}" />
                        </div>
                        <div class="col-md-4">
                            <label class="small mb-2" for="sector_representative">Sector representative <span style="color: red;">*</span></label>
                            <input type="text" name="sector_representative" id="sector_representative" class="form-control py-2" value="{{ old('sector_representative', $seller->store->sector_representative) }}" required />
                        </div>
                        <div class="col-md-4">
                            <label class="small mb-2" for="location">Location Url <span style="color: red;">*</span></label>
                            <input id="location" name="location" class="form-control py-2" value="{{ old('location', $seller->store->location) }}" required />
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
                                    $workingDays = old('work_days', json_decode($seller->store->work_days, true) ?? []);
                                    @endphp

                                    @foreach($days as $day)
                                    <div class="form-check mx-1">
                                        <input class="form-check-input" type="checkbox" name="work_days[]" value="{{ $day }}" id="day_{{ $day }}" {{ in_array($day, $workingDays) ? 'checked' : '' }}>
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
                            @php
                            $workingHours = $seller->store ? $seller->store->work_hours : '';
                            list($fromTime, $toTime) = explode(' - ', $workingHours);

                            @endphp
                            <div class="d-flex gap-3">
                                <div class="flex-grow-1 me-3">
                                    <label class="small" for="working_hours_from">Working From</label>
                                    <input type="time" name="working_hours_from" id="working_hours_from" class="form-control py-2" value="{{ old('working_hours_from', date('H:i', strtotime($fromTime))) }}" required />
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <label class="small" for="working_hours_to">Working To</label>
                                    <input type="time" name="working_hours_to" id="working_hours_to" class="form-control py-2" value="{{ old('working_hours_to', date('H:i', strtotime($toTime))) }}" required />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row gx-4 mb-4">
                        <div class="col-md-6">
                            <label class="small mb-2" for="facebook">Facebook</label>
                            <input type="text" name="facebook" id="facebook" class="form-control py-2" value="{{ old('facebook', $seller->store->facebook) }}" />
                        </div>
                        <div class="col-md-6">
                            <label class="small mb-2" for="instagram">Instagram</label>
                            <input type="text" name="instagram" id="instagram" class="form-control py-2" value="{{ old('instagram', $seller->store->instagram) }}" />
                        </div>
                    </div>

                    <div class="row gx-4 mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="contract_img">Contract Image</label>
                                <div class="input-group">
                                    <input type="file" class="form-control py-2" name="contract_img" id="contract_img" accept="image/*" onchange="previewImage(this, 'contract-preview')">
                                </div>
                                @error('contract_img')
                                <span class="text-danger small">{{$message}}</span>
                                @enderror
                                <div class="mt-2">
                                    @if($seller->store->contract_img)
                                    <img src="{{ $seller->store->contract_img }}" id="contract-preview" class="img-thumbnail" style="max-height: 200px;" alt="Contract Image" onload="this.classList.remove('d-none');">
                                    @else
                                    <img src="" id="contract-preview" class="img-thumbnail d-none" style="max-height: 200px;" alt="Contract Image Preview">
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="store_img">Sector/Store Image</label>
                                <div class="input-group">
                                    <input type="file" class="form-control py-2" name="store_img" id="store_img" accept="image/*" onchange="previewImage(this, 'store-preview')">
                                </div>
                                @error('store_img')
                                <span class="text-danger small">{{$message}}</span>
                                @enderror
                                <div class="mt-2">
                                    @if($seller->store->store_img)
                                    <img src="{{ $seller->store->store_img }}" id="store-preview" class="img-thumbnail" style="max-height: 200px;" alt="Store Image" onload="this.classList.remove('d-none');">
                                    @else
                                    <img src="" id="store-preview" class="img-thumbnail d-none" style="max-height: 200px;" alt="Store Image Preview">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary px-4 py-2">Update Seller</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
    function previewImage(input, previewId) {
        const file = input.files[0];
        const preview = document.getElementById(previewId);

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('d-none'); // Show the image
            }
            reader.readAsDataURL(file);
        } else {
            preview.src = ""; // Clear the preview if no file is selected
            preview.classList.add('d-none'); // Hide the image
        }
    }


    document.addEventListener('DOMContentLoaded', function() {
        const countrySelect = document.getElementById('country_id');
        const citySelect = document.getElementById('city_id');
        const oldCityId = @json(old('city_id', $seller -> store -> city));

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
