@extends('main/admin/layout/master')
@section('title')
    Edit Shop Images
@endsection
@section('content')
<div class="p-2">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" style="font-size:11pt;" role="alert">
            {{session('success')}}
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" style="font-size:11pt;" role="alert">
            {{session('warning')}}
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('danger'))
        <div class="alert alert-danger alert-dismissible fade show" style="font-size:11pt;" role="alert">
            {{session('danger')}}
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger alert-dismissible fade show" style="font-size:11pt;" role="alert">
                {{ $error }}
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endforeach
    @endif
</div>

<div class="view-order">
    <div class="pb-2 d-flex justify-content-between flex-wrap">
        <p class="mt-1">
            <a href="{{route('admin#shopList')}}" class="text-decoration-none">List</a> >
            <a href="{{route('admin#shopView',$shop->id)}}" class="text-decoration-none">View</a> >
            <span>Edit</span>
        </p>
        <button class="btn btn-outline-success mb-2" data-bs-toggle="modal" data-bs-target="#add">Add Image</button>
    </div>
    <div class="row">
        @foreach ($shop->image as $item)
            <div class="col-md-3 mb-4">
                <form method="post" action="{{route('admin#shopImageUpdate')}}" class="p-2 rounded parents card mb-4" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="py-2">
                        <img src="{{asset('storage/shop/'.$item)}}" class="image w-100" alt="Shop Image">
                    </div>
                    <div class="py-2">
                        <input type="file" name="image" class="form-control input_image" accept="image/*" required>
                        <input type="hidden" name="id" value="{{$shop->id}}">
                        <input type="hidden" name="index" value="{{$loop->index}}">
                    </div>
                    <div class="py-2">
                        <button class="btn btn-outline-success">Update</button>
                        <button class="btn btn-outline-danger" type="button" data-bs-toggle="modal" data-bs-target="#delete{{$loop->index}}">Delete</button>
                    </div>
                </form>
                <div class="modal fade" id="delete{{$loop->index}}" tabindex="-1" aria-labelledby="delete{{$loop->index}}Label" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h1 class="modal-title fs-5 d-block w-100 text-center" id="delete{{$loop->index}}Label">Shop Image Delete</h1>
                          <button type="button" class="close-btn border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('admin#shopImageDelete')}}" method="post">
                                @csrf
                                @method('DELETE')
                                <p class=" text-danger">
                                    Are you sure to delete this shop image. Please enter your password to verify that you are admin.
                                </p>
                                <div class="form-group mb-3">
                                    <label class="mb-1 fw-semibold">Password</label>
                                    <input id="" type="password" class="form-control" name="password" placeholder="Enter your password">
                                </div>

                                <input type="hidden" name="id" value="{{$shop->id}}">
                                <input type="hidden" name="index" value="{{$loop->index}}">

                                <button class="btn btn-danger w-100 border-0">Delete</button>
                            </form>
                        </div>
                      </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="add" tabindex="-1" aria-labelledby="addLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5 d-block w-100 text-center" id="addLabel">Add Shop Image</h1>
          <button type="button" class="close-btn border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <form action="{{route('admin#shopImageAdd')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row py-2 parents">
                    <div class="col-6">
                        <img src="{{asset('admin/image/default.jpg')}}" class="image w-100" alt="">
                    </div>
                    <div class="col-6 m-auto">
                        <input type="file" name="image" class="form-control input_image" accept="image/*">
                    </div>
                </div>
                <input type="hidden" name="id" value="{{$shop->id}}">
                <button class="btn w-100 border-0 mt-2" id="update-btn" >Add</button>
            </form>
        </div>
      </div>
    </div>
</div>
@endsection
@section('script')
<script>
            $('.input_image').change(function(){
                $parents=$(this).parents('.parents')
                $parents.find('.image').attr("src",window.URL.createObjectURL(this.files[0]));
                // document.getElementById('image').src = window.URL.createObjectURL(this.files[0]);
            })

</script>
@endsection
