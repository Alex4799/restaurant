@extends('main/admin/layout/master')
@section('title')
    View Transfer
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
        <a href="{{route('admin#transferList')}}" class="text-decoration-none">List</a> >
        <span>View</span>
    </p>
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card w-100">
                <div class="card-header text-center fs-5 fw-bold">
                    View Transfer
                </div>
                <div class="card-body">
                    <h6>Id - {{$transfer->id}}</h6>
                    <h6>Send Store - {{$transfer->send_store_name}}</h6>
                    <h6>Receive Store - {{$transfer->receive_store_name}}</h6>
                    <h6>Additional Fees - {{$transfer->additional_fees}} {{$transfer->currency}}</h6>
                    <h6>Total - {{$transfer->total_price}} {{$transfer->currency}}</h6>
                </div>
                <div class="border-top pt-2 pe-2 d-flex align-items-center justify-content-end gap-1">
                    <button class="btn @if($transfer->status==0) btn-warning @elseif ($transfer->status==1) btn-success @else btn-danger @endif">
                        @if($transfer->status==0) Panding @elseif($transfer->status==1) Success @else Reject @endif
                    </button>
                    @if ($transfer->status==0)
                        <button class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#update-transfer">Update</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-between mt-5">
        <p class="today-sale"><i class="fa-solid fa-parachute-box me-2"></i>Product Detail</p>
        <div>
            @if ($transfer->status==0)
                <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#add-transfer-item">Add</button>
            @endif
        </div>
    </div>
    <div>
        <table id="table-list">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Purchase Price</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transferItem as $item)
                    <tr>
                        <td>
                            @if ($item->image!=null)
                                <img style="width:70px;height:70px" src="{{asset('storage/product/'.$item->image)}}" class="img-thumbnail" alt="">
                            @else
                                <div style="background: {{$item->default_image}};width:70px;height:70px">
                                </div>
                            @endif
                        </td>
                        <td>{{$item->product_name}}</td>
                        <td>{{$item->qty}}</td>
                        <td>{{$item->price}} {{$item->currency}}</td>
                        <td>{{$item->total_price}} {{$item->currency}}</td>
                        <td>
                            <h6 class="@if($item->status==0) text-warning @elseif ($item->status==1) text-success @else text-danger @endif">
                                @if($item->status==0) Panding @elseif($item->status==1) Success @else Reject @endif
                            </h6>
                        </td>
                        <td>
                            @if ($transfer->status==0)
                                @if ($item->status!=2)
                                    <a href="" data-bs-toggle="modal" data-bs-target="#edit{{$item->id}}" class="text-decoration-none">Edit</a> |
                                @endif
                                <a href="" data-bs-toggle="modal" data-bs-target="#delete{{$item->id}}" class="text-danger text-decoration-none" >Delete</a>
                            @endif
                        </td>
                        <div class="modal fade" id="edit{{$item->id}}" tabindex="-1" aria-labelledby="edit{{$item->id}}Label" aria-hidden="true">
                            <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                <h1 class="modal-title fs-5 d-block w-100 text-center" id="edit{{$item->id}}Label">Update Transfer Product</h1>
                                <button type="button" class="close-btn border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{route('admin#transferItemUpdate')}}" method="POST" class="mt-1">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group mb-4">
                                            <label for="" class="mb-1 fw-semibold">Store Product</label>
                                            <input type="text" value="{{$item->product_name}}" class="form-control" disabled>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label for="" class="mb-1 fw-semibold">QTY</label>
                                            <input type="number" value="{{$item->qty}}" name="qty" class="form-control" min="1">
                                        </div>
                                        <div class="form-group mb-4">
                                            <label for="" class="mb-1 fw-semibold">Status</label>
                                            <div class="d-flex align-items-center">
                                                <select name="status" id="" class="form-control">
                                                    <option value="0" @if ($item->status==0) selected @endif>Pending</option>
                                                    <option value="1" @if ($item->status==1) selected @endif>Success</option>
                                                    <option value="2" @if ($item->status==2) selected @endif>Reject</option>
                                                </select>
                                                <i class="fa-solid fa-chevron-down" id="method-drop-icon2"></i>
                                            </div>
                                        </div>
                                        <input type="hidden" value="{{$item->store_item_id}}" name="store_item_id">
                                        <input type="hidden" name="transfer_id" value="{{$transfer->id}}">
                                        <input type="hidden" name="id" value="{{$item->id}}">
                                        <button type="submit" class="btn add-btn w-100 my-2">Update</button>
                                    </form>

                                </div>
                            </div>
                            </div>
                        </div>
                        <div class="modal fade" id="delete{{$item->id}}" tabindex="-1" aria-labelledby="delete{{$item->id}}Label" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5 d-block w-100 text-center" id="delete{{$item->id}}Label">Category Delete</h1>
                                    <button type="button" class="close-btn border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{route('admin#transferItemDelete')}}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <p class=" text-danger">
                                            Are you sure to delete this item. Please enter your password to verify that you are admin.
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
<div class="modal fade" id="update-transfer" tabindex="-1" aria-labelledby="update-transferLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5 d-block w-100 text-center" id="update-transferLabel">Update Purchase</h1>
          <button type="button" class="close-btn border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <form action="{{route('admin#transferUpdate')}}" method="POST" class="mt-1">
                @csrf
                @method('PUT')
                <div class="form-group mb-4">
                    <label for="" class="mb-1 fw-semibold">Additional Fees</label>
                    <input type="number" name="additional_fees" class="form-control" value="{{$transfer->additional_fees}}" min="0">
                </div>
                <div class="form-group mb-4">
                    <label for="" class="mb-1 fw-semibold">Currency</label>
                    <div class="d-flex align-items-center">
                        <select name="currency" class="form-control">
                            @foreach ($currency as $item)
                                <option value="{{$item->currency_code}}" @if ($transfer->currency==$item->currency_code) selected @endif>{{$item->currency_code}}</option>
                            @endforeach
                        </select>
                        <i class="fa-solid fa-chevron-down" id="method-drop-icon2"></i>
                    </div>
                </div>
                <div class="form-group mb-4">
                    <label for="name" class="form-label mb-1">Stauts</label>
                    <select name="status" id="" class="form-control">
                        <option value="0" @if ($transfer->status==0) selected @endif>Pending</option>
                        <option value="1" @if ($transfer->status==1) selected @endif>Success</option>
                        <option value="2" @if ($transfer->status==2) selected @endif>Reject</option>
                    </select>
                </div>
                <input type="hidden" value="{{$transfer->id}}" name="id">
                <button type="submit" class="btn add-btn w-100 my-2">Update</button>
            </form>
        </div>
      </div>
    </div>
</div>
<div class="modal fade" id="add-transfer-item" tabindex="-1" aria-labelledby="add-transfer-itemLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5 d-block w-100 text-center" id="add-transfer-itemLabel">Add Transfer Product</h1>
          <button type="button" class="close-btn border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <form action="{{route('admin#transferItemCreate')}}" method="POST" class="mt-1">
                @csrf
                <div class="form-group mb-4">
                    <label for="" class="mb-1 fw-semibold">Product</label>
                    <div class="d-flex align-items-center">
                        <select name="store_item_id" id="" class="form-control">
                            @foreach ($storeItem as $item)
                                <option value="{{$item->id}}">{{$item->product_name}} ({{$item->qty}})</option>
                            @endforeach
                        </select>
                        <i class="fa-solid fa-chevron-down" id="method-drop-icon2"></i>
                    </div>
                </div>
                <div class="form-group mb-4">
                    <label for="" class="mb-1 fw-semibold">QTY</label>
                    <input type="number" value="1" name="qty" class="form-control" min="1">
                </div>
                <div class="form-group mb-4">
                    <label for="" class="mb-1 fw-semibold">Status</label>
                    <div class="d-flex align-items-center">
                        <select name="status" id="" class="form-control">
                            <option value="0">Pending</option>
                            <option value="1">Success</option>
                        </select>
                        <i class="fa-solid fa-chevron-down" id="method-drop-icon2"></i>
                    </div>
                </div>
                <input type="hidden" name="transfer_id" value="{{$transfer->id}}">
                <button type="submit" class="btn add-btn w-100 my-2">Add</button>
            </form>

        </div>
      </div>
    </div>
</div>
@endsection
@section('script')

@endsection
