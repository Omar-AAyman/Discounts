@extends('layout')
@section('content')

<main>


        <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

        @if ($errors->has('fail'))
                                <div class="alert alert-danger">
                                    {{ $errors->first('fail') }}
                                </div>
                            @endif 


                  <div class="row m-5">
                            <div class="col-xl-4">
                                <!-- Profile picture card-->
                                <div class="card mb-4 mb-xl-0">
                                    <div class="card-header">{{$option->key}}</div>
                                    <div class="card-body text-center">
                                        <!-- Profile picture image-->
                                        <img id="product-image" width="160" height="160" class="img-account-profile  mb-1" src="{{ isset($option->img)? asset('optionImages/'.$option->img): '/assets/img/noimg.jpg' }}" alt="main pic" />
                                        <!-- Profile picture help block-->
                                        <!-- Profile picture upload button-->
                                        <form  method="POST" action="{{ route('options.updateImageOption', $option->id ) }}" enctype="multipart/form-data" id="image-form">
                                        @csrf
                                        @method('PUT')
                                        <label for="img" class="btn btn-primary btn-sm">
                                            Upload New Image
                                        </label>
                                        <input style="display: none;" type="file" name="img" id="img" class="form-control-file" multiple onchange="updateProfileImage(event);">
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-8">
                                <!-- Account details card-->
                                <div class="card mb-4">
                                    <div class="card-header">Image Option</div>
                                    <div class="card-body">
                                       
                                        <!-- Form Row-->
                                           
                                            <div class="row gx-3 mb-3">
                                               
                                                <div class="col-12">
                                                   
                                                    <input class="form-control"  type="text"  value="{{ $option->key }}" readonly />
                                                  
                                                </div>
                                                
                                                
                                            </div>
                                           
                                            <div class="row gx-3 mb-3">
                                            <div class="col-12">
                                            <button class="btn btn-primary" type="submit">Save changes</button></div></div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

</main>

<script>
    function updateProfileImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('product-image');
            output.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);

        // Submit form after selecting image
    }
</script>
@endsection                       