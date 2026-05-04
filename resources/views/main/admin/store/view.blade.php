@extends('main/admin/layout/master')
@section('title')
    Store View
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
        <a href="{{route('admin#shopView',$store->shop_id)}}" class="text-decoration-none">List</a> >
        <span>View</span>
    </p>
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card w-100">
                <div class="card-header text-center fs-5 fw-bold">
                    Store View
                </div>
                <div class="card-body">
                    <h6>Name - {{$store->name}}</h6>
                    <h6>Shop Name - {{$store->shop_name}}</h6>
                    <h6>Manger - {{$store->manager_name}}</h6>
                    <h6>Address - {{$store->address}}</h6>
                    <h6>Contact -
                        <div>{!!$store->contact!!}</div>
                    </h6>
                    <h6>Status - @if ($store->active==0)
                        <span class="text-danger">Not Active</span>
                    @else
                        <span class="text-success">Active</span>
                    @endif</h6>
                </div>
                <div class="border-top pt-2 pe-2 d-flex align-items-center justify-content-end">
                    <a href="javascipt::void(0);" class="me-3">
                        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#edit">Edit</button>
                    </a>
                    <a href="javascipt::void(0);" class="me-3">
                        <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#delete">Delete</button>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-between mt-5">
        <p class="today-sale"><i class="fa-solid fa-parachute-box me-2"></i>Store Products</p>
        <div>
            <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#add">Add</button>
            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#reduce-storeItem">Reduce</button>
            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#damage-reduce-storeItem">Damage Reduce</button>
        </div>
    </div>
    <div class="py-2">
        <div>
            <h4>Selling Product</h4>
        </div>
        <table id="table-list2">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Purchase Price</th>
                    <th>Selling Price</th>
                    <th>Currency</th>
                    <th>Profit</th>
                    <th>Instock Level</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($storeItem as $item)
                    @if (!$item->type)
                        <tr>
                            <td>{{$loop->index+1}}</td>
                            <td>{{$item->product_name}}</td>
                            <td>@if ($item->instock_type) &infin; @else {{$item->qty}} @endif</td>
                            <td>{{$item->purchase_price}}</td>
                            <td>{{$item->selling_price}}</td>
                            <td>{{$item->currency}}</td>
                            <td>{{$item->profit}}</td>
                            <td>@if ($item->instock_type) &infin; @else {{$item->instock_level}} @endif</td>
                            <td>
                                @if ($item->active==0)
                                    <span class="text-danger">Not Active</span>
                                @else
                                    <span class="text-success">Active</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <button class="btn text-primary fs-5 border-0" data-bs-toggle="modal" data-bs-target="#editStoreProduct{{$item->id}}"><i class="fa-regular fa-pen-to-square"></i></button>
                                    @if ($item->barcode!=null)
                                        <button class="btn text-primary fs-5 border-0" data-bs-toggle="modal" data-bs-target="#generateBarcode{{$item->id}}"><i class="fa-solid fa-barcode"></i></button>
                                    @endif
                                </div>
                            </td>
                            <div class="modal fade" id="generateBarcode{{$item->id}}" tabindex="-1" aria-labelledby="generateBarcode{{$item->id}}Label" aria-hidden="true">
                                <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h1 class="modal-title fs-5 d-block w-100 text-center" id="generateBarcode{{$item->id}}Label">Barcode Generate</h1>
                                    <button type="button" class="close-btn border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{route('admin#storeItemBarcodeGenerate')}}" method="POST" target="_blank" class="mt-1">
                                            @csrf
                                            <div class="mb-4">
                                                <label for="" class="mb-1 fw-semibold">Barcode</label>
                                                <input type="text" class="form-control" value="{{$item->barcode}}" disabled>
                                            </div>
                                            <div class="mb-4">
                                                <label for="" class="mb-1 fw-semibold">Count</label>
                                                <input type="number" min="1" name="count" value="1" class="form-control">
                                            </div>
                                            <input type="hidden" name="id" value="{{$item->id}}">
                                            <button type="submit" class="btn add-btn w-100 my-2">Generate</button>
                                        </form>

                                    </div>
                                </div>
                                </div>
                            </div>
                            <div class="modal fade" id="editStoreProduct{{$item->id}}" tabindex="-1" aria-labelledby="editStoreProduct{{$item->id}}Label" aria-hidden="true">
                                <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h1 class="modal-title fs-5 d-block w-100 text-center" id="editStoreProduct{{$item->id}}Label">Store Product Edit</h1>
                                    <button type="button" class="close-btn border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{route('admin#storeItemUpdate')}}" method="POST" class="mt-1">
                                            @csrf
                                            @method('PUT')
                                            <div class="mb-4">
                                                <label for="" class="mb-1 fw-semibold">Product Name</label>
                                                <input type="text" class="form-control" value="{{$item->product_name}}" disabled>
                                            </div>
                                            <div class="form-group mb-4">
                                                <label for="" class="mb-1 fw-semibold">Purchase Price</label>
                                                <input type="number" value="{{$item->purchase_price}}" name="purchase_price" class="form-control">
                                            </div>
                                            <div class="form-group mb-4">
                                                <label for="" class="mb-1 fw-semibold">Selling Price</label>
                                                <input type="number" value="{{$item->selling_price}}" name="selling_price" class="form-control">
                                            </div>
                                            <div class="form-group mb-4">
                                                <label for="" class="mb-1 fw-semibold">Currency</label>
                                                <div class="d-flex align-items-center">
                                                    <select name="currency" id="" class="form-control">
                                                        @foreach ($currency as $currencyItem)
                                                            <option value="{{$currencyItem->currency_code}}" @if ($item->currency==$currencyItem->currency_code) selected @endif>{{$currencyItem->currency_code}}</option>
                                                        @endforeach
                                                    </select>
                                                    <i class="fa-solid fa-chevron-down" id="method-drop-icon2"></i>
                                                </div>
                                            </div>
                                            <div class="form-group mb-4">
                                                <input type="checkbox" name="instock_type" @if ($item->instock_type) checked @endif>
                                                <label for="">No Need Instock</label>
                                            </div>
                                            <div class="form-group mb-4">
                                                <label for="" class="mb-1 fw-semibold">Qty</label>
                                                <input type="number" value="{{$item->qty}}" min="1" name="qty" class="form-control">
                                            </div>
                                            <div class="form-group mb-4">
                                                <label for="" class="mb-1 fw-semibold">Instock Level</label>
                                                <input type="number" value="{{$item->instock_level}}" name="instock_level" class="form-control">
                                            </div>
                                            <div class="form-group mb-4">
                                                <label for="" class="mb-1 fw-semibold">Barcode</label>
                                                <input type="text" value="{{$item->barcode}}" name="barcode" class="form-control">
                                            </div>
                                            <div class="form-group mb-4">
                                                <input type="checkbox" name="type" @if ($item->type) checked @endif>
                                                <label for="">No Selling</label>
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
                                            <button type="submit" class="btn add-btn w-100 my-2">Update</button>
                                        </form>

                                    </div>
                                </div>
                                </div>
                            </div>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="py-2">
        <div>
            <h4>No Selling Product</h4>
        </div>
        <table id="table-list2">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Purchase Price</th>
                    <th>Currency</th>
                    <th>Instock Level</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($storeItem as $item)
                    @if ($item->type)
                        <tr>
                            <td>{{$loop->index+1}}</td>
                            <td>{{$item->product_name}}</td>
                            <td>@if ($item->instock_type) &infin; @else {{$item->qty}} @endif</td>
                            <td>{{$item->purchase_price}}</td>
                            <td>{{$item->currency}}</td>
                            <td>@if ($item->instock_type) &infin; @else {{$item->instock_level}} @endif</td>
                            <td>
                                @if ($item->active==0)
                                    <span class="text-danger">Not Active</span>
                                @else
                                    <span class="text-success">Active</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <button class="btn text-primary fs-5 border-0" data-bs-toggle="modal" data-bs-target="#editStoreProduct{{$item->id}}"><i class="fa-regular fa-pen-to-square"></i></button>
                                    @if ($item->barcode!=null)
                                        <button class="btn text-primary fs-5 border-0" data-bs-toggle="modal" data-bs-target="#generateBarcode{{$item->id}}"><i class="fa-solid fa-barcode"></i></button>
                                    @endif
                                </div>
                            </td>
                            <div class="modal fade" id="generateBarcode{{$item->id}}" tabindex="-1" aria-labelledby="generateBarcode{{$item->id}}Label" aria-hidden="true">
                                <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h1 class="modal-title fs-5 d-block w-100 text-center" id="generateBarcode{{$item->id}}Label">Barcode Generate</h1>
                                    <button type="button" class="close-btn border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{route('admin#storeItemBarcodeGenerate')}}" method="POST" target="_blank" class="mt-1">
                                            @csrf
                                            <div class="mb-4">
                                                <label for="" class="mb-1 fw-semibold">Barcode</label>
                                                <input type="text" class="form-control" value="{{$item->barcode}}" disabled>
                                            </div>
                                            <div class="mb-4">
                                                <label for="" class="mb-1 fw-semibold">Count</label>
                                                <input type="number" min="1" name="count" value="1" class="form-control">
                                            </div>
                                            <input type="hidden" name="id" value="{{$item->id}}">
                                            <button type="submit" class="btn add-btn w-100 my-2">Generate</button>
                                        </form>

                                    </div>
                                </div>
                                </div>
                            </div>
                            <div class="modal fade" id="editStoreProduct{{$item->id}}" tabindex="-1" aria-labelledby="editStoreProduct{{$item->id}}Label" aria-hidden="true">
                                <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h1 class="modal-title fs-5 d-block w-100 text-center" id="editStoreProduct{{$item->id}}Label">Store Product Edit</h1>
                                    <button type="button" class="close-btn border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{route('admin#storeItemUpdate')}}" method="POST" class="mt-1">
                                            @csrf
                                            @method('PUT')
                                            <div class="mb-4">
                                                <label for="" class="mb-1 fw-semibold">Product Name</label>
                                                <input type="text" class="form-control" value="{{$item->product_name}}" disabled>
                                            </div>
                                            <div class="form-group mb-4">
                                                <label for="" class="mb-1 fw-semibold">Purchase Price</label>
                                                <input type="number" value="{{$item->purchase_price}}" name="purchase_price" class="form-control">
                                            </div>
                                            <div class="form-group mb-4">
                                                <label for="" class="mb-1 fw-semibold">Selling Price</label>
                                                <input type="number" value="{{$item->selling_price}}" name="selling_price" class="form-control">
                                            </div>
                                            <div class="form-group mb-4">
                                                <label for="" class="mb-1 fw-semibold">Currency</label>
                                                <div class="d-flex align-items-center">
                                                    <select name="currency" id="" class="form-control">
                                                        @foreach ($currency as $currencyItem)
                                                            <option value="{{$currencyItem->currency_code}}" @if ($item->currency==$currencyItem->currency_code) selected @endif>{{$currencyItem->currency_code}}</option>
                                                        @endforeach
                                                    </select>
                                                    <i class="fa-solid fa-chevron-down" id="method-drop-icon2"></i>
                                                </div>
                                            </div>
                                            <div class="form-group mb-4">
                                                <input type="checkbox" name="instock_type" @if ($item->instock_type) checked @endif>
                                                <label for="">No Need Instock</label>
                                            </div>
                                            <div class="form-group mb-4">
                                                <label for="" class="mb-1 fw-semibold">Qty</label>
                                                <input type="number" value="{{$item->qty}}" min="1" name="qty" class="form-control">
                                            </div>
                                            <div class="form-group mb-4">
                                                <label for="" class="mb-1 fw-semibold">Instock Level</label>
                                                <input type="number" value="{{$item->instock_level}}" name="instock_level" class="form-control">
                                            </div>
                                            <div class="form-group mb-4">
                                                <label for="" class="mb-1 fw-semibold">Barcode</label>
                                                <input type="text" value="{{$item->barcode}}" name="barcode" class="form-control">
                                            </div>
                                            <div class="form-group mb-4">
                                                <input type="checkbox" name="type" @if ($item->type) checked @endif>
                                                <label for="">No Selling</label>
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
                                            <button type="submit" class="btn add-btn w-100 my-2">Update</button>
                                        </form>

                                    </div>
                                </div>
                                </div>
                            </div>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="modal fade" id="add" tabindex="-1" aria-labelledby="addLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5 d-block w-100 text-center" id="addLabel">Add Store Product</h1>
          <button type="button" class="close-btn border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <form action="{{route('admin#storeItemCreate')}}" method="POST" class="mt-1">
                @csrf
                <div class="form-group mb-4">
                    <label for="" class="mb-1 fw-semibold">Product</label>
                    <div class="d-flex align-items-center">
                        <select name="product_id" id="" class="form-control">
                            @foreach ($product as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                        <i class="fa-solid fa-chevron-down" id="method-drop-icon2"></i>
                    </div>
                </div>
                <div class="form-group mb-4">
                    <input type="checkbox" name="instock_type" value="1" id="no_instock">
                    <label for="">No Need Instock</label>
                </div>
                <div class="form-group mb-4">
                    <label for="" class="mb-1 fw-semibold">QTY</label>
                    <input type="number" value="1" name="qty" class="form-control" min="1">
                </div>
                <div class="form-group mb-4">
                    <label for="" class="mb-1 fw-semibold">Supplier</label>
                    <div class="d-flex align-items-center">
                        <select name="supplier_id" id="" class="form-control">
                            <option value="">Choose Supplier</option>
                            @foreach ($supplier as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                        <i class="fa-solid fa-chevron-down" id="method-drop-icon2"></i>
                    </div>
                </div>
                <div class="form-group mb-4">
                    <label for="" class="mb-1 fw-semibold">Purchase Price (For One Item)</label>
                    <input type="number" name="purchase_price" class="form-control">
                </div>
                <div class="form-group mb-4">
                    <label for="" class="mb-1 fw-semibold">Selling Price</label>
                    <input type="number" name="selling_price" class="form-control">
                </div>
                <div class="form-group mb-4">
                    <label for="" class="mb-1 fw-semibold">Currency</label>
                    <div class="d-flex align-items-center">
                        <select name="currency" id="" class="form-control">
                            @foreach ($currency as $item)
                                <option value="{{$item->currency_code}}">{{$item->currency_code}}</option>
                            @endforeach
                        </select>
                        <i class="fa-solid fa-chevron-down" id="method-drop-icon2"></i>
                    </div>
                </div>
                <div class="form-group mb-4">
                    <label class="mb-1">Instock Level</label>
                    <input id="" name="instock_level" type="text" value="{{$item->instock_level}}" class="form-control @error('instock_level') is-invalid @enderror" placeholder="Enter product selling price">
                    @error('instock_level')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                 <div class="form-group mb-4">
                    <label class="mb-1">Barcode</label>
                    <input id="" name="barcode" type="text" value="{{$item->barcode}}" class="form-control @error('barcode') is-invalid @enderror" placeholder="Enter product barcode">
                    @error('barcode')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div class="form-group mb-4">
                    <input type="checkbox" name="type" value="1" id="no_selling">
                    <label for="">No Selling Item</label>
                </div>
                <div class="form-group mb-4">
                    <label for="" class="mb-1 fw-semibold">Status</label>
                    <div class="d-flex align-items-center">
                        <select name="active" id="" class="form-control">
                            <option value="0">Not Active</option>
                            <option value="1">Active</option>
                        </select>
                        <i class="fa-solid fa-chevron-down" id="method-drop-icon2"></i>
                    </div>
                </div>
                <input type="hidden" name="store_id" value="{{$store->id}}">
                <button type="submit" class="btn add-btn w-100 my-2">Add</button>
            </form>

        </div>
      </div>
    </div>
</div>
<div class="modal fade" id="edit" tabindex="-1" aria-labelledby="editLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5 d-block w-100 text-center" id="editLabel">Store Edit</h1>
          <button type="button" class="close-btn border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <form action="{{route('admin#storeUpdate')}}" method="POST" class="mt-1">
                @csrf
                @method('PUT')
                <div class="form-group mb-4">
                    <label for="" class="mb-1 fw-semibold">Name</label>
                    <input type="text" value="{{$store->name}}" name="name" class="form-control">
                </div>
                <div class="form-group mb-4">
                    <label for="" class="mb-1 fw-semibold">Contact</label>
                    <textarea class="editor" name="contact">{!!$store->contact!!}</textarea>
                </div>
                <div class="form-group mb-4">
                    <label for="" class="mb-1 fw-semibold">Address</label>
                    <textarea name="address" id="" class="form-control">{!!$store->address!!}</textarea>
                </div>
                <div class="form-group mb-4">
                    <label for="" class="mb-1 fw-semibold">Manager</label>
                    <div class="d-flex align-items-center">
                        <select name="manager_id" class="form-control">
                            <option value="">Choose Manager Account</option>
                            @foreach ($manager as $item)
                                <option value="{{$item->id}}" @if ($store->manager_id==$item->id) selected @endif>{{$item->name}}</option>
                            @endforeach
                        </select>
                        <i class="fa-solid fa-chevron-down" id="method-drop-icon2"></i>
                    </div>
                </div>
                <div class="form-group mb-4">
                    <label for="" class="mb-1 fw-semibold">Shop</label>
                    <div class="d-flex align-items-center">
                        <select name="shop_id" class="form-control">
                            <option value="">Choose Shop</option>
                            @foreach ($shop as $item)
                                <option value="{{$item->id}}" @if ($store->shop_id==$item->id) selected @endif>{{$item->name}}</option>
                            @endforeach
                        </select>
                        <i class="fa-solid fa-chevron-down" id="method-drop-icon2"></i>
                    </div>
                </div>
                <div class=" form-group mb-4">
                    <label class="mb-1 fw-semibold">Status</label>
                    <div class="d-flex align-items-center">
                        <select name="active" id="" class="form-control">
                            <option value="0" @if ($store->active==0) selected @endif>Not Active</option>
                            <option value="1" @if ($store->active==1) selected @endif>Active</option>
                        </select>
                        <i class="fa-solid fa-chevron-down" id="method-drop-icon2"></i>
                    </div>
                </div>
                <input type="hidden" name="id" value="{{$store->id}}">
                <button type="submit" class="btn add-btn w-100 my-2">Update</button>
            </form>

        </div>
      </div>
    </div>
</div>
<div class="modal fade" id="delete" tabindex="-1" aria-labelledby="deleteLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5 d-block w-100 text-center" id="deleteLabel">Store Delete</h1>
          <button type="button" class="close-btn border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <form action="{{route('admin#storeDelete')}}" method="post">
                @csrf
                @method('DELETE')
                <p class=" text-danger">
                    Are you sure to delete this store. Please enter your password to verify that you are admin.
                </p>
                <div class="form-group mb-3">
                    <label class="mb-1 fw-semibold">Password</label>
                    <input id="" type="password" class="form-control" name="password" placeholder="Enter your password">
                </div>

                <input type="hidden" name="id" value="{{$store->id}}">

                <button class="btn btn-danger w-100 border-0">Delete</button>
            </form>
        </div>
      </div>
    </div>
</div>
<div class="modal fade" id="damage-reduce-storeItem" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="create-storeItemLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title d-block w-100 text-center" id="create-storeItemLabel">Reduce Damage Store Item</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('admin#reduceCreate')}}" method="post" class="mt-1">
                    @csrf
                    <div class="form-group mb-4">
                        <label class="mb-1">Product</label>
                        <select name="store_item_id" id="" class="form-control @error('store_item_id') is-invalid @enderror">
                            <option value="">Choose Product</option>
                            @foreach ($storeItem as $item)
                                @if ($item->qty>0 && $item->type!=1)
                                    <option value="{{$item->id}}">{{$item->product_name}} (Max - @if ($item->instock_type) &infin; @else {{$item->qty}} @endif)</option>
                                @endif
                            @endforeach
                        </select>
                        @error('store_item_id')
                            <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                    <div class="form-group mb-4">
                        <label class="mb-1">QTY</label>
                        <input id="" name="qty" type="number" min="1" class="form-control @error('qty') is-invalid @enderror" placeholder="Enter product qty">
                        @error('qty')
                            <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                    <input type="hidden" name="type" value="1">
                    <button type="submit" class="btn btn-primary w-100 my-2">Create</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="reduce-storeItem" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="create-storeItemLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title d-block w-100 text-center" id="create-storeItemLabel">Reduce Store Item</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('admin#reduceCreate')}}" method="post" class="mt-1">
                    @csrf
                    <div class="form-group mb-4">
                        <label class="mb-1">Product</label>
                        <select name="store_item_id" id="" class="form-control @error('store_item_id') is-invalid @enderror">
                            <option value="">Choose Product</option>
                            @foreach ($storeItem as $item)
                                @if ($item->qty>0 && $item->type==1)
                                    <option value="{{$item->id}}">{{$item->product_name}} (Max - {{$item->qty}})</option>
                                @endif
                            @endforeach
                        </select>
                        @error('store_item_id')
                            <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                    <div class="form-group mb-4">
                        <label class="mb-1">QTY</label>
                        <input id="" name="qty" type="number" min="1" class="form-control @error('qty') is-invalid @enderror" placeholder="Enter product qty">
                        @error('qty')
                            <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                    <input type="hidden" name="type" value="0">
                    <button type="submit" class="btn btn-primary w-100 my-2">Create</button>
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

    let editors=document.querySelectorAll( '.editor' );
    editors.forEach(element => {
        ClassicEditor
            .create( element , {
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
    });
</script>
@endsection
