@extends('layout-delegate')

@section('content')


    <main>
   

        <!-- Main page content-->
        <div class="container mt-n5">


                    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
                    <div class="card">
                    <div class="card-header">Add a new seller</div>
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

                    <form action="{{ route('delegates.addSeller') }}" method="POST" >
                        @csrf

                        <div class="row gx-3 mb-3">

                        <div class="col-md-6">
                        <label class="small mb-1" for="seller_name">Seller's Name <span style="color: red;">*</span> </label>
                        <input type="text" name="seller_name" id="seller_name" class="form-control" value="{{old('seller_name')}}" required/>
                        @error('seller_name')
                                {{$message}}
                        @enderror
                        </div>

                        <div class="col-md-6">
                        <label class="small mb-1" for="store_name">Store's Name <span style="color: red;">*</span> </label>
                        <input type="text" name="store_name" id="store_name" class="form-control" value="{{old('store_name')}}" required/>
                        @error('store_name')
                                {{$message}}
                        @enderror
                        </div>
                        </div>

                        <div class="row gx-3 mb-3">

                        <div class="col-md-6">
                            <label class="small mb-1" for="section_id">Section <span style="color: red;">*</span></label>
                            <select name="section_id" id="section_id" class="form-control form-control-solid" required>
                                        <option value="" >Select a section </option>
                                    @foreach($sections as $section)
                                    <option value="{{$section->id}}">{{$section->name}}</option>
                                    @endforeach
                                </select>
                                
                        </div>

                        <div class="col-md-6">
                        <label class="small mb-1" for="licensed_operator_number">Licensed operator number</label>
                        <input type="text" name="licensed_operator_number" id="licensed_operator_number" class="form-control" value="{{old('licensed_operator_number')}}"/>
                     
                        </div>



                    </div>
                <div class="row gx-3 mb-3">
                <div class="col-md-6">
                        <label class="small mb-1" for="sector_representative">Sector representative <span style="color: red;">*</span></label>
                        <input type="text" name="sector_representative" id="sector_representative" class="form-control" value="{{old('sector_representative')}}" required/>

                                
                </div>
                        <div class="col-md-6">
                        <label class="small mb-1" for="location">Location <span style="color: red;">*</span></label>
                        <input id="location"  name="location" class="form-control" value="{{old('location')}}" required/>
                                   @error('location')
                                {{$message}}
                        @enderror
                        </div>
                        </div>



            <div class="row gx-3 mb-3">
                <div class="col-md-6">
                        <label class="small mb-1" for="phone_number1">Phone number 1 <span style="color: red;">*</span></label>
                        <input type="text" name="phone_number1" id="phone_number1" class="form-control" value="{{old('phone_number1')}}" required/>

                                
                </div>
                        <div class="col-md-6">
                        <label class="small mb-1" for="phone_number2">Phone number 2</label>
                        <input type="text" name="phone_number2" id="phone_number2" class="form-control" value="{{old('phone_number2')}}" />
               
                        </div>
                        </div>




            <div class="row gx-3 mb-3">
                <div class="col-md-6">
                        <label class="small mb-1" for="phone_number1">Email <span style="color: red;">*</span></label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                
                </div>

                <div class="col-md-6">
                        <label class="small mb-1" for="working_hours">Working hours <span style="color: red;">*</span></label>
                        <input type="text" name="working_hours" id="working_hours" class="form-control" value="{{old('working_hours')}}" required/>

                                
                </div>
            </div>



          <div class="row gx-3 mb-3">
                <div class="col-md-6">
                        <label class="small mb-1" for="working_days">Working days <span style="color: red;">*</span></label>
                        <input type="text" name="working_days" id="working_days" class="form-control" value="{{old('working_days')}}" required/>

                                
                </div>

                <div class="col-md-6">
                        <label class="small mb-1" for="facebook">Facebook</label>
                        <input type="text" name="facebook" id="facebook" class="form-control" value="{{old('facebook')}}" />

                                
                </div>
          </div>


          <div class="row gx-3 mb-3">
                <div class="col-md-6">
                        <label class="small mb-1" for="instagram">Instagram</label>
                        <input type="text" name="instagram" id="instagram" class="form-control" value="{{old('instagram')}}"/>

                                
                </div>

                <div class="col-md-6" style="margin-top: 30.5px;">
                        <label class="btn btn-primary btn-sm"  for="contract_img">Contract Image</label>
                        <input style="display: none;" type="file" name="contract_img" id="contract_img" />
                        <span id="file-name" style="margin-left: 10px;"></span>

                                
                </div>
          </div>

          <div class="row gx-3 mb-3">

            <div class="col-md-6">
                            <label class="btn btn-primary btn-sm" for="store_img">Sector/Store Image</label>
                            <input style="display: none;" type="file" name="store_img" id="store_img" />
                            <span id="store-file-name" style="margin-left: 10px;"></span>

                                    
                    </div>

 
 
                        <div class="col-md-6">
                        <button type="submit" class="btn btn-primary btn-sm">Send</button>
                    </div></div>
                    </form>

            </div>
        </div>
        </div>
    </main>
    <script>
    // For Contract Image
    document.getElementById('contract_img').addEventListener('change', function() {
        var fileName = this.files[0].name;
        document.getElementById('file-name').textContent = fileName;
    });

    // For Store Image
    document.getElementById('store_img').addEventListener('change', function() {
        var fileName = this.files[0].name;
        document.getElementById('store-file-name').textContent = fileName;
    });
</script>


@endsection


