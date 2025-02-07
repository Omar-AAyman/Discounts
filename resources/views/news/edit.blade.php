@extends('layout')
@section('content')
<main>

  <div class="container mt-5">

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
                                    <div class="card-header">News Picture</div>
                                    <div class="card-body text-center">
                                        <!-- Profile picture image-->
                                         @if(isset($news->img))
                                          <img id="product-image" width="160" height="160" class="img-account-profile  mb-1" src="{{ asset('newsImages/'.$news->img) }}" alt="main pic" />
                                        @else 
                                          <img id="product-image" width="160" height="160" class="img-account-profile  mb-1" src="{{ asset('assets/img/noimg.jpg') }}" alt="main pic" />
                                        @endif  

                                       
                                        <!-- Profile picture help block-->
                                        <!-- Profile picture upload button-->
                                        <form  method="POST" action="{{ route('news.update',$news->uuid ) }}" enctype="multipart/form-data" id="image-form">
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
                                    <div class="card-header">News Details</div>
                                    <div class="card-body">
                                       
                                        <!-- Form Row-->
                                           
                                            <div class="row gx-3 mb-3">
                                               
                                                <div class="col-md-6">
                                                <label for="title" class="form-label"> Title </label>

                                                   
                                                    <input class="form-control" name="title"  type="text"  value="{{ $news->title }}" required />
                                                  
                                                </div>

                                                <div class="col-md-6">
                                                <label for="description" class="form-label"> Description </label>

                                                   
                                                   <textarea class="form-control" name="description"  type="text" required>{{$news->description}}</textarea>
                                                 
                                               </div>
                                                
                                                
                                            </div>

                                            <div class="row gx-3 mb-3">
                                               
                                               <div class="col-md-6">
                                              
                                              
                                               <label for="is_online" class="form-label "> Is Online </label>
                                                <input class="form-check-input ml-3" name="is_online"  type="checkbox" {{$news->is_online?'checked':''}} />
                                                 
                                               </div></div>

                                           
                                            <div class="row gx-3 mb-3">
                                            <div class="col-12">
                                            <button class="btn btn-primary btn-sm" type="submit">Save Changes</button></div></div>
                                        </form>
                                    </div>
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