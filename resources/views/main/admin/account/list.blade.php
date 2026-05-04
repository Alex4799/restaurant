@extends('main/admin/layout/master')
@section('title')
    Admin List
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
                <div class="py-1 px-3">
                    <span>All ( {{count($user)}} )</span>
                </div>
                @if (Auth::user()->position!='seller')
                    <button data-bs-toggle="modal" data-bs-target="#create">Create</button>
                @endif
            </div>
            <form action="" id="search-table">
                <div class="group">
                    <svg class="icon" aria-hidden="true" viewBox="0 0 24 24"><g><path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path></g></svg>
                    <input placeholder="Search by name ..." name="search_key" value="{{request('search_key')}}" type="search" class="input">
                </div>
            </form>
        </div>
        <div>
            <table id="table-list2">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Position</th>
                        <th>Shop</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="py-4">
                    @foreach ($user as $item)
                        <tr>
                            <td>{{$item->id}}</td>
                            <td>{{$item->name}}</td>
                            <td>{{$item->email}}</td>
                            <td>{{$item->position}}</td>
                            <td>
                                @if ($item->shop_id==null)
                                    All
                                @else
                                    {{$item->shop_name}}
                                @endif
                            </td>
                            <td>{{$item->phone}}</td>
                            <td>{{$item->address}}</td>
                            <td>
                                @if (Auth::user()->id!=$item->id)
                                    @if (Auth::user()->position=='admin'||Auth::user()->position=='developer'||(Auth::user()->position=='manager'&&$item->position=='seller'))
                                        <a href="" data-bs-toggle="modal" data-bs-target="#update{{$item->id}}" class="text-decoration-none">Edit</a> |
                                        <a href="" data-bs-toggle="modal" data-bs-target="#delete{{$item->id}}" class="text-danger text-decoration-none">Delete</a>
                                    @endif
                                @endif
                            </td>
                            <div class="modal fade" id="update{{$item->id}}" tabindex="-1" aria-labelledby="update{{$item->id}}Label" aria-hidden="true">
                                <div class="modal-dialog">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h1 class="modal-title fs-5 d-block w-100 text-center" id="update{{$item->id}}Label">Account Update</h1>
                                      <button type="button" class="close-btn border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{route('admin#update')}}" method="post">
                                            @csrf
                                            @method('PUT')
                                            <div class="form-group mb-3">
                                                <label class="mb-1 fw-semibold">Name</label>
                                                <input id="" type="text" class="form-control" value="{{$item->name}}" name="name" placeholder="Enter name">
                                            </div>

                                            <div class="form-group mb-4">
                                                <label class="mb-1 fw-semibold">Email</label>
                                                <input id="" type="email" class="form-control" value="{{$item->email}}" name="email" placeholder="Enter email">
                                            </div>

                                             <div class="form-group mb-4">
                                                <label class="mb-1 fw-semibold">Position</label>
                                                <div class="d-flex align-items-center">
                                                    <select name="position" id="" class="form-control">
                                                        <option value="">Choose Position</option>
                                                        @if (Auth::user()->position=='admin'||Auth::user()->position=="developer")
                                                            <option value="admin" @if ($item->position=='admin') selected @endif>Admin</option>
                                                            <option value="manager" @if ($item->position=='manager') selected @endif>Manager</option>
                                                        @endif
                                                        <option value="seller" @if ($item->position=='seller') selected @endif>Seller</option>
                                                    </select>
                                                    <i class="fa-solid fa-chevron-down" id="method-drop-icon2"></i>
                                                </div>
                                            </div>

                                            <div class="form-group mb-4">
                                                <label class="mb-1 fw-semibold">Shop</label>
                                                <div class="d-flex align-items-center">
                                                    <select name="shop_id" id="" class="form-control">
                                                        <option value="">All</option>
                                                        @foreach ($shop as $shopItem)
                                                            <option value="{{$shopItem->id}}" @if ($shopItem->id==$item->shop_id) selected @endif>{{$shopItem->name}}</option>
                                                        @endforeach
                                                    </select>
                                                    <i class="fa-solid fa-chevron-down" id="method-drop-icon2"></i>
                                                </div>
                                            </div>

                                            <div class="form-group mb-4">
                                                <label class="mb-1 fw-semibold">Phone</label>
                                                <input id="" type="text" class="form-control" value="{{$item->phone}}" name="phone" placeholder="Enter phone">
                                            </div>

                                            <div class="form-group mb-4">
                                                <label class="mb-1 fw-semibold">Address</label>
                                                <input id="" type="text" class="form-control" value="{{$item->address}}" name="address" placeholder="Enter address">
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
                                      <h1 class="modal-title fs-5 d-block w-100 text-center" id="delete{{$item->id}}Label">Account Delete</h1>
                                      <button type="button" class="close-btn border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{route('admin#delete')}}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <p class=" text-danger">
                                                Are you sure to delete this account. Please enter your password to verify that you are admin.
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
<!-- Modal -->
<div class="modal fade" id="create" tabindex="-1" aria-labelledby="createLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
          <h1 class="modal-title fs-5 d-block w-100 text-center" id="createLabel">Account Create</h1>
          <button type="button" class="close-btn border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <form action="{{route('admin#create')}}" method="post">
                @csrf
                <div class="form-group mb-3">
                    <label class="mb-1 fw-semibold">Name</label>
                    <input id="" type="text" class="form-control" name="name" placeholder="Enter name">
                </div>

                <div class="form-group mb-4">
                    <label class="mb-1 fw-semibold">Email</label>
                    <input id="" type="email" class="form-control" name="email" placeholder="Enter email">
                </div>

                 <div class="form-group mb-4">
                    <label class="mb-1 fw-semibold">Position</label>
                    <div class="d-flex align-items-center">
                        <select name="position" id="" class="form-control">
                            <option value="">Choose Position</option>
                            @if (Auth::user()->position=='admin'||Auth::user()->position=='developer')
                                <option value="admin">Admin</option>
                                <option value="manager">Manager</option>
                            @endif
                            <option value="seller">Seller</option>
                        </select>
                        <i class="fa-solid fa-chevron-down" id="method-drop-icon2"></i>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label class="mb-1 fw-semibold">Shop</label>
                    <div class="d-flex align-items-center">
                        <select name="shop_id" id="" class="form-control">
                            <option value="">All</option>
                            @foreach ($shop as $shopItem)
                                <option value="{{$shopItem->id}}">{{$shopItem->name}}</option>
                            @endforeach
                        </select>
                        <i class="fa-solid fa-chevron-down" id="method-drop-icon2"></i>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label class="mb-1 fw-semibold">Phone</label>
                    <input id="" type="text" class="form-control" name="phone" placeholder="Enter phone">
                </div>

                <div class="form-group mb-4">
                    <label class="mb-1 fw-semibold">Address</label>
                    <input id="" type="text" class="form-control" name="address" placeholder="Enter address">
                </div>

                <div class="form-group mb-4">
                    <label class="control-label mb-1 fw-semibold">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter your current password">
                </div>

                <button class="btn w-100 border-0" id="update-btn" >Create</button>
            </form>
        </div>
      </div>
    </div>
</div>
@endsection
