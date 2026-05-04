@extends('main/admin/layout/master')
@section('title')
    Tax List
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
                    <span>All ( {{count($tax)}} )</span>
                </div>
            </div>
            <form action=""  id="search-table">
                <div class="group">
                    <svg class="icon" aria-hidden="true" viewBox="0 0 24 24"><g><path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path></g></svg>
                    <input placeholder="Search by name ..." value="{{request('search_key')}}" name="search_key" type="search" class="input">
                </div>
            </form>
            {{-- <div>
                <form action="" class="d-flex align-items-center" id="filter-content">
                    <div class="d-flex align-items-center w-100">
                        <select name="" id="" class="form-control">
                            <option value="">Filter By Type</option>
                            <option value="">Included in the price</option>
                            <option value="">Added to the price</option>
                        </select>
                        <i class="fa-solid fa-chevron-down" id="method-drop-icon2"></i>
                    </div>
                    <button id="filter">Filter</button>
                </form>
            </div> --}}
        </div>
        <div class="container-fluid ps-3">
            <div class="row">
                <div class="col-lg-3 category-form">
                    <h6>Add new tax</h6>
                    <hr>
                    <form action="{{route('admin#taxCreate')}}" method="post">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="" class="mb-1">Name</label>
                            <input type="text" name="name" class="form-control">
                        </div>
                        {{-- <div class="form-group mb-3">
                            <label for="" class="mb-1">Type</label>
                            <div class="d-flex align-items-center">
                                <select name="" id="" class="form-control">
                                    <option value="" disabled selected>Choose a type</option>
                                    <option value="">Included in the price</option>
                                    <option value="">Added to the price</option>
                                </select>
                                <i class="fa-solid fa-chevron-down" id="method-drop-icon2"></i>
                            </div>
                        </div> --}}
                        <div class="form-group mb-3">
                            <label for="" class="mb-1">Percentage (%) </label>
                            <input type="number" name="percentage" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="" class="m-1">Amount</label>
                            <input type="number" name="amount" class="form-control">
                        </div>
                        <button class="btn add-btn w-100 mt-3">Create</button>
                    </form>
                </div>
                <div class="col-lg-9">
                    <table id="table-list2">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Tax</th>
                                <th>Default</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="py-4">
                            @foreach ($tax as $item)
                                <tr>
                                    <td>{{$item->id}}</td>
                                    <td>
                                        <span>{{$item->name}}</span>
                                    </td>
                                    <td>
                                        @if ($item->percentage!=null)
                                            {{$item->percentage}}%
                                        @else
                                            {{$item->amount}}MMK
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->default==0)
                                            <span class="text-danger">False</span>
                                        @else
                                            <span class="text-success">True</span>
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
                                        <a href="" data-bs-toggle="modal" data-bs-target="#edit{{$item->id}}" class="text-decoration-none">Edit</a> |
                                        <a href="" data-bs-toggle="modal" data-bs-target="#delete{{$item->id}}" class="text-danger text-decoration-none" >Delete</a>
                                    </td>
                                    <div class="modal fade" id="edit{{$item->id}}" tabindex="-1" aria-labelledby="edit{{$item->id}}Label" aria-hidden="true">
                                        <div class="modal-dialog">
                                          <div class="modal-content">
                                            <div class="modal-header">
                                              <h1 class="modal-title fs-5 d-block w-100 text-center" id="edit{{$item->id}}Label">Tax Edit</h1>
                                              <button type="button" class="close-btn border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{route('admin#taxUpdate')}}" method="post" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="form-group mb-4">
                                                        <label class="mb-1 fw-semibold">Name</label>
                                                        <input id="" type="text" value="{{$item->name}}" class="form-control" name="name" placeholder="Enter name">
                                                    </div>
                                                    <div class="form-group mb-4">
                                                        <label for="" class="mb-1 fw-semibold">Percentage (%) </label>
                                                        <input type="number" value="{{$item->percentage}}" name="percentage" class="form-control">
                                                    </div>
                                                    <div class="form-group mb-4">
                                                        <label for="" class="m-1 fw-semibold">Amount</label>
                                                        <input type="number" value="{{$item->amount}}" name="amount" class="form-control">
                                                    </div>
                                                    <div class=" form-group mb-4">
                                                        <label class="mb-1 fw-semibold">Default</label>
                                                        <div class="d-flex align-items-center">
                                                            <select name="default" id="" class="form-control">
                                                                <option value="0" @if ($item->default==0) selected @endif>False</option>
                                                                <option value="1" @if ($item->default==1) selected @endif>True</option>
                                                            </select>
                                                            <i class="fa-solid fa-chevron-down" id="method-drop-icon2"></i>
                                                        </div>
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
                                              <h1 class="modal-title fs-5 d-block w-100 text-center" id="delete{{$item->id}}Label">Tax Delete</h1>
                                              <button type="button" class="close-btn border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{route('admin#taxDelete')}}" method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <p class=" text-danger">
                                                        Are you sure to delete this tax. Please enter your password to verify that you are admin.
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
