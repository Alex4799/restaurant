@extends('main/admin/layout/master')
@section('title')
    Product View
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
    <p>
        <a href="{{route('admin#productList')}}" class="text-decoration-none">List</a> >
        <span>View</span>
    </p>
    <div class="row ">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header text-center fs-5 fw-bold">
                    Product View
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-4">
                            @if ($product->image!=null)
                                <img src="{{asset('storage/product/'.$product->image)}}" class="d-block w-100 img-thumbnail bg-transparent" alt="Product Image">
                            @else
                            <div class="d-flex justify-content-center">
                                <div style="background: {{$product->default_image}}; height: 200px; width: 200px;"></div>
                            </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h6>Name - {{$product->name}}</h6>
                            <h6>Category - {{$product->category_name}}</h6>
                            <h6>Description -
                                <div>{!!$product->description!!}</div>
                            </h6>
                            <h6>Status -
                                @if ($product->active==0)
                                    <span class="text-danger">Not Active</span>
                                @else
                                    <span class="text-success">Active</span>
                                @endif
                            </h6>
                            <div class="d-flex flex-wrap justify-content-end border-top pt-3 pe-2">
                                <button class="btn btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#edit">Edit</button>
                                <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#delete">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="edit" tabindex="-1" aria-labelledby="editLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
          <h1 class="modal-title fs-5 d-block w-100 text-center" id="editLabel">Product Edit</h1>
          <button type="button" class="close-btn border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <form action="{{route('admin#productUpdate')}}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group mb-3">
                    <label class="mb-1 fw-semibold">Name</label>
                    <input id="" type="text" class="form-control" value="{{$product->name}}" name="name" placeholder="Enter name">
                </div>
                <div class="form-group mb-4">
                    <label class="mb-2 fw-semibold">Image</label>
                    <div class="row">
                        <div class="col-6">
                            <img src="{{asset('storage/product/'.$product->image)}}" id="image" class="w-75 mx-auto d-block img-thumbnail" alt="">
                        </div>
                        <div class="col-6 m-auto">
                            <input type="file" name="image" id="input_image" class="form-control" accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="form-group mb-4">
                    <label class="mb-1 fw-semibold">Category</label>
                    <div class="d-flex align-items-center w-100">
                        <select name="category" id="" class="form-control">
                            <option value="">Select Catagory</option>
                            @foreach ($category as $item)
                                <option value="{{$item->id}}" @if ($item->id==$product->category_id) selected @endif>{{$item->name}}</option>
                            @endforeach
                        </select>
                        <i class="fa-solid fa-chevron-down" id="method-drop-icon2"></i>
                    </div>
                </div>
                <div class="form-group mb-4">
                    <label class="mb-1 fw-semibold">Description</label>
                    <textarea id="editor" class="form-control" name="description" cols="30" rows="10">{!!$product->description!!}</textarea>
                </div>
                <div class="form-group mb-4">
                    <div>
                        <label class="mb-1">Default Image Color</label>
                    </div>
                    <input id="" name="default_image" type="color" value="{{$product->default_image}}">
                </div>
                <div class=" form-group mb-4">
                    <label class="mb-1 fw-semibold">Status</label>
                    <div class="d-flex align-items-center">
                        <select name="active" id="" class="form-control">
                            <option value="0" @if ($product->active==0) selected @endif>Not Active</option>
                            <option value="1" @if ($product->active==1) selected @endif>Active</option>
                        </select>
                        <i class="fa-solid fa-chevron-down" id="method-drop-icon2"></i>
                    </div>
                </div>
                <input type="hidden" name="id" value="{{$product->id}}">
                <button class="btn w-100 border-0" id="update-btn" >Update</button>
            </form>
        </div>
      </div>
    </div>
</div>
<div class="modal fade" id="delete" tabindex="-1" aria-labelledby="deleteLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
       <div class="modal-header">
          <h1 class="modal-title fs-5 d-block w-100 text-center" id="deleteLabel">Product Delete</h1>
          <button type="button" class="close-btn border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <form action="{{route('admin#productDelete')}}" method="post">
                @csrf
                @method('DELETE')
                <p class=" text-danger">
                    Are you sure to delete this product. Please enter your password to verify that you are admin.
                </p>
                <div class="form-group mb-3">
                    <label class="mb-1 fw-semibold">Password</label>
                    <input id="" type="password" class="form-control" name="password" placeholder="Enter your password">
                </div>

                <input type="hidden" name="id" value="{{$product->id}}">

                <button class="btn btn-danger w-100 border-0">Delete</button>
            </form>
        </div>
      </div>
    </div>
</div>
@endsection
@section('script')
        <script type="module">
            import {
                ClassicEditor,
                Essentials,
                Bold,
                Italic,
                Font,
                Paragraph
            } from 'ckeditor5';

            ClassicEditor
                .create( document.querySelector( '#editor' ), {
                    plugins: [ Essentials, Bold, Italic, Font, Paragraph ],
                    toolbar: {
                        items: [
                            'undo', 'redo', '|', 'bold', 'italic', '|',
                            'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor'
                        ]
                    }
                } )
                .then( /* ... */ )
                .catch( /* ... */ );
        </script>
@endsection
