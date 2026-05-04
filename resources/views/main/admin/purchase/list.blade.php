@extends('main.admin.layout.master')
@section('title')
    Purchase History
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
        <!-- purchase list start  -->
        <div class="table-container">
            <div class="table-content">
                <div class="table-title">
                    <div class="d-flex align-items-center">
                        <div class="py-1 px-3">
                            <span>All ( {{count($purchase)}} )</span>
                        </div>
                        <button data-bs-toggle="modal" data-bs-target="#add-purchase">Create</button>
                    </div>
                    <form action="" id="search-table">
                        <div class="d-flex align-items-center">
                            <div>
                                <span>PID</span>
                            </div>
                            <div class="group">
                                <svg class="icon" aria-hidden="true" viewBox="0 0 24 24"><g><path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path></g></svg>
                                <input placeholder="Search by product ID ..." name="search_key" value="{{request('search_key')}}" type="search" class="input">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="d-flex align-items-center justify-content-end mt-2">
                    <div>
                        <form action="" id="filter-content">
                            <div class="d-flex align-items-center w-100 me-4">
                                <select name="store_id" id="" class="form-control">
                                    <option value="">Select Store</option>
                                    <option value="">All</option>
                                    @foreach ($store as $item)
                                        <option value="{{$item->id}}" @if ($item->id==request('store_id')) selected @endif>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                <i class="fa-solid fa-chevron-down" id="method-drop-icon2"></i>
                            </div>
                            <div class="d-flex align-items-center w-100">
                                <select name="status" id="" class="form-control">
                                    <option value="">Select Status</option>
                                    <option value="">All</option>
                                    <option value="0" @if (request('status')==='0') selected @endif>Pending</option>
                                    <option value="1" @if (request('status')=='1') selected @endif>Success</option>
                                    <option value="2" @if (request('status')=='2') selected @endif>Reject</option>
                                </select>
                                <i class="fa-solid fa-chevron-down" id="method-drop-icon2"></i>
                            </div>
                            <button class="btn btn-success" id="filter">Filter</button>
                        </form>
                    </div>
                </div>
                <div>
                    <table id="table-list2">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Supplier</th>
                                <th>Store</th>
                                <th>Total Price</th>
                                <th>currency</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="py-4">
                            @foreach ($purchase as $item)
                                <tr>
                                    <td>{{$item->id}}</td>
                                    <td>{{$item->supplier_name}}</td>
                                    <td>{{$item->store_name}}</td>
                                    <td>{{$item->total_price}}</td>
                                    <td>{{$item->currency}}</td>
                                    <td>{{$item->created_at}}</td>
                                    <td>
                                        @if ($item->status==0)
                                            <span class="text-warning">Pending</span>
                                        @elseif($item->status==1)
                                            <span class="text-success">Success</span>
                                        @else
                                            <span class="text-danger">Reject</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{route('admin#purchaseView',$item->id)}}" class="text-bg-primary px-2 py-1 rounded" title="view more">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- purchase list end  -->

        <!-- purchase create modal -->
        <div class="modal fade" id="add-purchase" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="add-purchaseLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5 d-block w-100 text-center" id="add-purchaseLabel">Add New Purchase</h1>
                        <button type="button" class="close-btn border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('admin#purchaseCreate')}}" method="POST" class="mt-1">
                            @csrf
                            <div class="form-group mb-4">
                                <label for="" class="mb-1 fw-semibold">Supplier Name</label>
                                <div class="d-flex align-items-center">
                                    <select name="supplier_id" id="" class="form-control">
                                        <option value="" disabled selected>Choose a supplier</option>
                                        @foreach ($supplier as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    <i class="fa-solid fa-chevron-down" id="method-drop-icon2"></i>
                                </div>
                            </div>
                            <div class="form-group mb-4">
                                <label for="" class="mb-1 fw-semibold">Store Name</label>
                                <div class="d-flex align-items-center">
                                    <select name="store_id" id="" class="form-control">
                                        <option value="" disabled selected>Choose a store</option>
                                        @foreach ($store as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    <i class="fa-solid fa-chevron-down" id="method-drop-icon2"></i>
                                </div>
                            </div>
                            <div class="form-group mb-4">
                                <label for="" class="mb-1 fw-semibold">Currency</label>
                                <div class="d-flex align-items-center">
                                    <select name="currency" class="form-control">
                                        @foreach ($currency as $item)
                                            <option value="{{$item->currency_code}}">{{$item->currency_code}}</option>
                                        @endforeach
                                    </select>
                                    <i class="fa-solid fa-chevron-down" id="method-drop-icon2"></i>
                                </div>
                            </div>
                            <button type="submit" class="btn add-btn w-100 my-2">Create</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
@endsection
