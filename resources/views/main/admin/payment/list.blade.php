@extends('main.admin.layout.master')
@section('title')
    Payment Method
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

<div class="table-container">
    <div class="table-content">
        <div class="table-title">
            <div class="d-flex align-items-center">
                <div class="py-1 px-3 mb-2">
                    <span>All ( {{count($payment)}} )</span>
                </div>
            </div>
            <form action="" id="search-table">
                <div class="group">
                    <svg class="icon" aria-hidden="true" viewBox="0 0 24 24"><g><path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path></g></svg>
                    <input placeholder="Search by username ..." value="{{request('search_key')}}" name="search_key" type="search" class="input">
                </div>
            </form>
        </div>
        <div class="container-fluid ps-3">
            <div class="row">
                <div class="col-lg-3 category-form">
                    <h6>Add new payment</h6>
                    <hr>
                    <form action="{{route('admin#paymentCreate')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="" class="mb-1">Shop</label>
                            <div class="d-flex align-items-center">
                                <select name="shop_id" id="" class="form-control">
                                    <option value="" disabled selected>Choose shop</option>
                                    <option value="0">Online Payment</option>
                                    @foreach ($shop as $item)
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                                <i class="fa-solid fa-chevron-down" id="method-drop-icon2"></i>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="" class="mb-1">Payment name</label>
                            <input type="text" name="name" class="form-control">
                        </div>
                        <div class="form-group mb-3">
                            <label for="" class="mb-1">Username</label>
                            <input type="text" name="user_name" class="form-control">
                        </div>
                        <div class="form-group mb-3">
                            <label for="" class="mb-1">Number</label>
                            <input type="text" name="number" class="form-control">
                        </div>
                        <div class="form-group mb-3 parents">
                            <label for="" class="">QR Image</label>
                            <div class="py-2">
                                <img src="{{asset('image/default.jpg')}}" class="d-block mx-auto w-50 img-thumbnail" id="image" alt="">
                            </div>
                            <input type="file" name="image" class="form-control input_image" accept="image/*">
                        </div>
                        <button class="btn add-btn w-100 mt-2">Create</button>
                    </form>
                </div>
                <div class="col-lg-9">
                    <table id="table-list2">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Payment name</th>
                                <th>Username</th>
                                <th>Number</th>
                                <th>Shop</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="py-4">
                            @foreach ($payment as $item)
                                <tr>
                                    <td>{{$item->id}}</td>
                                    <td>
                                        <span>{{$item->name}}</span>
                                    </td>
                                    <td>{{$item->user_name}}</td>
                                    <td>{{$item->number}}</td>
                                    <td>
                                        @if ($item->shop_id!==0)
                                            {{$item->shop_name}}
                                        @else
                                            Online Payment
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->active==0)
                                            <span class="text-danger">Not Active</span>
                                        @else
                                            <span class="text-success">Active</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->id!=1)
                                            <div class="t-action2">
                                                <a href="" data-bs-toggle="modal" data-bs-target="#edit{{$item->id}}" class="text-decoration-none">Edit</a> |
                                                <a href="" data-bs-toggle="modal" data-bs-target="#delete{{$item->id}}" class="text-danger text-decoration-none">Delete</a>
                                            </div>
                                        @endif
                                    </td>
                                    <div class="modal fade" id="edit{{$item->id}}" tabindex="-1" aria-labelledby="edit{{$item->id}}Label" aria-hidden="true">
                                        <div class="modal-dialog">
                                          <div class="modal-content">
                                            <div class="modal-header">
                                              <h1 class="modal-title fs-5 d-block w-100 text-center" id="edit{{$item->id}}Label">Payment Method Edit</h1>
                                              <button type="button" class="close-btn border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{route('admin#paymentUpdate')}}" method="post" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="form-group mb-4">
                                                        <label for="" class="mb-1 fw-semibold">Shop</label>
                                                        <div class="d-flex align-items-center">
                                                            <select name="shop_id" id="" class="form-control">
                                                                <option value="" disabled selected>Choose shop</option>
                                                                <option value="0" @if ($item->shop_id==0) selected @endif>Online Payment</option>
                                                                @foreach ($shop as $shopItem)
                                                                    <option value="{{$shopItem->id}}" @if ($item->shop_id==$shopItem->id) selected @endif>{{$shopItem->name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <i class="fa-solid fa-chevron-down" id="method-drop-icon2"></i>
                                                        </div>
                                                    </div>
                                                    <div class="form-group mb-4">
                                                        <label for="" class="mb-1 fw-semibold">Payment name</label>
                                                        <input type="text" name="name" value="{{$item->name}}" class="form-control">
                                                    </div>
                                                    <div class="form-group mb-4">
                                                        <label for="" class="mb-1 fw-semibold">Username</label>
                                                        <input type="text" name="user_name" value="{{$item->user_name}}" class="form-control">
                                                    </div>
                                                    <div class="form-group mb-4">
                                                        <label for="" class="mb-1 fw-semibold">Number</label>
                                                        <input type="text" name="number" value="{{$item->number}}" class="form-control">
                                                    </div>
                                                    <div class="form-group mb-4 parents">
                                                        <label for="" class="fw-semibold">QR Image</label>
                                                        <div class="py-2">
                                                            @if ($item->qr==null)
                                                                <img src="{{asset('image/default.jpg')}}" class="d-block w-50 mx-auto img-thumbnail image" id="image" alt="">
                                                            @else
                                                                <img src="{{asset('storage/qr/'.$item->qr)}}" class="d-block w-50 mx-auto img-thumbnail image" id="image" alt="">
                                                            @endif
                                                        </div>
                                                        <input type="file" name="image" class="form-control input_image" accept="image/*">
                                                    </div>
                                                    <div class=" form-group mb-4">
                                                        <label class="mb-1 fw-semibold">Status</label>
                                                        <div class="d-flex align-items-center">
                                                            <select name="active" id="" class="form-control">
                                                                <option value="0" @if ($item->active==0) selected @endif>Not Active</option>
                                                                <option value="1" @if ($item->active==1) selected @endif>Active</option>
                                                            </select>
                                                            <i class="fa-solid fa-chevron-down" id="method-drop-icon2"></i>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="id" value="{{$item->id}}">
                                                    <button class="btn w-100 border-0" id="update-btn" >Update</button>
                                                </form>
                                            </div>
                                          </div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="delete{{$item->id}}" tabindex="-1" aria-labelledby="delete{{$item->id}}Label" aria-hidden="true">
                                        <div class="modal-dialog">
                                          <div class="modal-content">
                                            <div class="modal-header">
                                              <h1 class="modal-title fs-5 d-block w-100 text-center" id="delete{{$item->id}}Label">Payment Method Delete</h1>
                                              <button type="button" class="close-btn border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{route('admin#paymentDelete')}}" method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <p class=" text-danger">
                                                        Are you sure to delete this payment method. Please enter your password to verify that you are admin.
                                                    </p>
                                                    <div class="form-group mb-3">
                                                        <label class="mb-1 fw-semibold">Password</label>
                                                        <input id="" type="password" class="form-control" name="password" placeholder="Enter your password">
                                                    </div>

                                                    <input type="hidden" name="id" value="{{$item->id}}">

                                                    <button class="btn btn-danger w-100 border-0">Delete</button>
                                                </form>
                                            </div>
                                          </div>
                                        </div>
                                    </div>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
    <script>
        $('.input_image').change(function(){
            $parents=$(this).parents('.parents');

            $parents.find('#image').attr("src",window.URL.createObjectURL(this.files[0]));
        })
    </script>
@endsection
