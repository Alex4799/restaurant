@extends('main.admin.layout.master')
@section('title')
    Category List
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
                    <span>All ( {{count($category)}} )</span>
                </div>
            </div>
            <form action="" id="search-table">
                <div class="group">
                    <svg class="icon" aria-hidden="true" viewBox="0 0 24 24"><g><path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path></g></svg>
                    <input placeholder="Search by name ..." type="search" name="search_key" value="{{request('search_key')}}" class="input">
                </div>
            </form>
        </div>
        <div class="container-fluid ps-3">
            <div class="row">
                <div class="col-lg-3 category-form">
                    <h6>Add new category</h6>
                    <hr>
                    <form action="{{route('admin#categoryCreate')}}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="" class="mb-1">Name</label>
                            <input type="text" class="form-control" name="name">
                        </div>
                        <button class="btn add-btn w-100 mt-3">Create</button>
                    </form>
                </div>
                <div class="col-lg-9">
                    <table id="table-list2">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Category name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="py-4">
                            @foreach ($category as $item)
                                <tr>
                                    <td>{{$item->id}}</td>
                                    <td>{{$item->name}}</td>
                                    <td>
                                        @if ($item->id!=1)
                                            <a href="" data-bs-toggle="modal" data-bs-target="#edit{{$item->id}}" class="text-decoration-none">Edit</a> |
                                            <a href="" data-bs-toggle="modal" data-bs-target="#delete{{$item->id}}" class="text-danger text-decoration-none" >Delete</a>
                                        @endif
                                    </td>
                                    <div class="modal fade" id="edit{{$item->id}}" tabindex="-1" aria-labelledby="edit{{$item->id}}Label" aria-hidden="true">
                                        <div class="modal-dialog">
                                          <div class="modal-content">
                                            <div class="modal-header">
                                              <h1 class="modal-title fs-5 d-block w-100 text-center" id="edit{{$item->id}}Label">Category Edit</h1>
                                              <button type="button" class="close-btn border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{route('admin#categoryUpdate')}}" method="post" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="form-group mb-3">
                                                        <label class="mb-1 fw-semibold">Name</label>
                                                        <input id="" type="text" value="{{$item->name}}" class="form-control" name="name" placeholder="Enter name">
                                                    </div>
                                                    <input type="hidden" name="id" value="{{$item->id}}">
                                                    <button class="btn w-100 border-0" id="update-btn" >Create</button>
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
                                                <form action="{{route('admin#categoryDelete')}}" method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <p class=" text-danger">
                                                        Are you sure to delete this category. If you delete this, the product of this category is moved into other. Please enter your password to verify that you are admin.
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
