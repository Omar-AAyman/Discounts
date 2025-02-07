@extends('layout')

@section('content')


    <main>
   

        <!-- Main page content-->
        <div class="container mt-n5">


                    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
                    <div class="card">
                    <div class="card-header">Attach store to this section</div>
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

                    <form action="{{ route('sections.attachStore',$section->uuid) }}" method="POST" >
                        @csrf
                        @method('PATCH')

                        <div class="row gx-3 mb-3">

            

                        <div class="col-md-6">
                            <label class="small mb-1" for="store_id">Store</label>
                            <select name="store_id" id="store_id" class="form-control form-control-solid" required>
                                        <option value="" >Select a store </option>
                                    @foreach($stores as $store)
                                    <option value="{{$store->id}}">{{$store->name}}</option>
                                    @endforeach
                                </select>
                                
                        </div>
                    
                

                  


                       
                        <div class="col-md-6" style="margin-top: 35px;">
                        <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                    </div></div>
                    </form>

            </div>
        </div>
        </div>
    </main>


@endsection


