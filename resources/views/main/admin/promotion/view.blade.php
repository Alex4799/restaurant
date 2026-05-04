@extends('main/admin/layout/master')
@section('title')
    Promotion View
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
        <a href="{{route('admin#promotionList')}}" class="text-decoration-none">List</a> >
        <span>View</span>
    </p>
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header text-center fs-5 fw-bold">
                    Promotion View
                </div>
                <div class="card-body pt-4">
                    <div class="row form-group">
                        <div class="col-lg-4 col-md-12 mb-4">
                            <div>
                                @if (isset($promotion->image))
                                    <div class="">
                                        <img src="{{asset('storage/promotion/'.$promotion->image)}}"  class="d-block w-100 img-thumbnail bg-transparent" alt="Promotion Image">
                                    </div>
                                @else
                                    <div class="">
                                        <img src="{{asset('admin/image/default.jpg')}}"  class="d-block w-100 img-thumbnail bg-transparent" alt="Promotion Image">
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-8 col-md-12 mb-1">
                            <div class="mb-3">
                                <h5>{{$promotion->name}}</h5>
                                <ul class="mt-3">
                                    <li class="mb-3">Promo Code - {{$promotion->promo_code}}</li>
                                    <li class="mb-3">Start Date - {{$promotion->start_date}}</li>
                                    <li class="mb-3">End Date - {{$promotion->end_date}}</li>
                                    <li class="mb-3">Shop -  @if ($promotion->shop_id==0) All @else {{$promotion->shop_name}} @endif</li>
                                    <li class="mb-3">Promotion -
                                        @if ($promotion->percentage!=null)
                                            {{$promotion->percentage}}%
                                        @else
                                            {{$promotion->amount}} MMK
                                        @endif
                                    </li>
                                </ul>
                                <p class="mt-3">{!!$promotion->description!!}</p>
                                <div class="d-flex align-items-center flex-wrap mt-4">
                                    <p class="me-5">Status :
                                        @if ($promotion->active==0)
                                            <span class="text-danger fw-semibold">Not Active</span>
                                        @else
                                            <span class="text-success fw-semibold">Active</span>
                                        @endif
                                    </p>
                                    <p class="ms-md-5">Feature :
                                        @if ($promotion->feature==0)
                                            <span class="text-danger fw-semibold">False</span>
                                        @else
                                            <span class="text-success fw-semibold">True</span>
                                        @endif
                                    </p>
                                </div>
                                <p class="mt-2">Default :
                                    @if ($promotion->default==0)
                                        <span class="text-danger fw-semibold">False</span>
                                    @else
                                        <span class="text-success fw-semibold">True</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap justify-content-end border-top pt-3 pe-2">
                        <button class="btn btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#edit">Edit</button>
                        <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#delete">Delete</button>
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
          <h1 class="modal-title fs-5 d-block w-100 text-center" id="editLabel">Promotion Edit</h1>
          <button type="button" class="close-btn border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <form action="{{route('admin#promotionUpdate')}}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group mb-4">
                    <label class="mb-1 fw-semibold">Name</label>
                    <input id="" type="text" class="form-control" value="{{$promotion->name}}" name="name" placeholder="Enter name">
                </div>
                <div class="form-group mb-4">
                    <label class="mb-2 fw-semibold">Image</label>
                    <div class="row">
                         <div class="col-6">
                            @if (isset($promotion->image))
                                <div class="">
                                    <img src="{{asset('storage/promotion/'.$promotion->image)}}" id="image" class="w-75 mx-auto d-block img-thumbnail" alt="Promotion Image">
                                </div>
                            @else
                                <div class="">
                                    <img src="{{asset('admin/image/default.jpg')}}" id="image" class="w-75 mx-auto d-block img-thumbnail" alt="Promotion Image">
                                </div>
                            @endif
                        </div>
                        <div class="col-6 m-auto">
                            <input type="file" name="image" id="input_image" class="form-control" accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="form-group mb-4">
                    <label for="" class="mb-1 fw-semibold">Shop</label>
                    <div class="d-flex align-items-center">
                        <select name="shop_id" id="" class="form-control">
                            <option value="0" @if ($promotion->shop_id==0) selected @endif>All</option>
                            @foreach ($shop as $item)
                                <option value="{{$item->id}}" @if ($promotion->shop_id==$item->id) selected @endif >{{$item->name}}</option>
                            @endforeach
                        </select>
                        <i class="fa-solid fa-chevron-down" id="method-drop-icon2"></i>
                    </div>
                </div>
                <div class="form-group mb-4">
                    <label class="mb-1 fw-semibold">Description</label>
                    <textarea id="editor" class="form-control" name="description" cols="30" rows="10">{!!$promotion->description!!}</textarea>
                </div>
                <div class="form-group mb-4">
                    <label class="mb-1 fw-semibold">Promo Code</label>
                    <input id="" type="text" class="form-control" value="{{$promotion->promo_code}}" name="promo_code" placeholder="Enter promo code">
                </div>
                <div class="form-group mb-4">
                    <label class="mb-1 fw-semibold">Percentage</label>
                    <input id="" type="text" class="form-control" value="{{$promotion->percentage}}" name="percentage" placeholder="Enter percentage">
                </div>
                <div class="form-group mb-4">
                    <label class="mb-1 fw-semibold">Amount</label>
                    <input id="" type="text" class="form-control" value="{{$promotion->amount}}" name="amount" placeholder="Enter amount">
                </div>
                <div class="form-group mb-4">
                    <label class="mb-1 fw-semibold">Start Date</label>
                    <input id="" type="date" class="form-control" value="{{$promotion->start_date}}" name="start_date" placeholder="Enter start date">
                </div>
                <div class="form-group mb-4">
                    <label class="mb-1 fw-semibold">End Date</label>
                    <input id="" type="date" class="form-control" value="{{$promotion->end_date}}" name="end_date" placeholder="Enter end date">
                </div>
                <div class=" form-group mb-4">
                    <label class="mb-1 fw-semibold">Status</label>
                    <div class="d-flex align-items-center">
                        <select name="active" id="" class="form-control">
                            <option value="0" @if ($promotion->active==0) selected @endif>Not Active</option>
                            <option value="1" @if ($promotion->active==1) selected @endif>Active</option>
                        </select>
                        <i class="fa-solid fa-chevron-down" id="method-drop-icon2"></i>
                    </div>
                </div>
                <div class=" form-group mb-4">
                    <label class="mb-1 fw-semibold">Default</label>
                    <div class="d-flex align-items-center">
                        <select name="default" id="" class="form-control">
                            <option value="0" @if ($promotion->default==0) selected @endif>False</option>
                            <option value="1" @if ($promotion->default==1) selected @endif>True</option>
                        </select>
                        <i class="fa-solid fa-chevron-down" id="method-drop-icon2"></i>
                    </div>
                </div>
                <div class=" form-group mb-4">
                    <label class="mb-1 fw-semibold">Feature</label>
                    <div class="d-flex align-items-center">
                        <select name="feature" id="" class="form-control">
                            <option value="0" @if ($promotion->feature==0) selected @endif>False</option>
                            <option value="1" @if ($promotion->feature==1) selected @endif>True</option>
                        </select>
                        <i class="fa-solid fa-chevron-down" id="method-drop-icon2"></i>
                    </div>
                </div>
                <input type="hidden" name="id" value="{{$promotion->id}}">
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
          <h1 class="modal-title fs-5 d-block w-100 text-center" id="deleteLabel">Promotion Delete</h1>
          <button type="button" class="close-btn border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <form action="{{route('admin#promotionDelete')}}" method="post">
                @csrf
                @method('DELETE')
                <p class=" text-danger">
                    Are you sure to delete this promotion. Please enter your password to verify that you are admin.
                </p>
                <div class="form-group mb-3">
                    <label class="mb-1 fw-semibold">Password</label>
                    <input id="" type="password" class="form-control" name="password" placeholder="Enter your password">
                </div>

                <input type="hidden" name="id" value="{{$promotion->id}}">

                <button class="btn btn-danger w-100 border-0">Delete</button>
            </form>
        </div>
      </div>
    </div>
</div>
@endsection
@section('script')
<script>
    $(document).ready(function(){
        $('#input_image').change(function(){
            document.getElementById('image').src = window.URL.createObjectURL(this.files[0]);
        })
    })
</script>
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
