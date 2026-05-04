@extends('main.admin.layout.master')
@section('title')
    Reduce
@endsection
@section('content')
<main class="content px-4 py-4 mb-5">
    <div class="row p-4 mb-4">
        <div class="col-md-6 py-2">
            <div class="shadow rounded p-4">
                <h5 class=" py-1">Total Reduce Count</h5>
                <div class="d-flex align-items-center gap-1">
                    <div class="shadow py-2 px-2 bg-primary rounded-circle d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-ticket fs-2 text-white"></i>
                    </div>
                    <h5 class="py-2">{{$normalReduce->total()}}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-6 py-2">
            <div class="shadow rounded p-4">
                <h5 class=" py-1">Total Reduce Price</h5>
                <div class="d-flex align-items-center gap-1">
                    <div class="shadow py-2 px-3 bg-primary rounded-circle d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-dollar-sign fs-2 text-white"></i>
                    </div>
                    <h5 class="py-2">{{number_format($normalReduceTotalPrice)}}</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="row p-4 mb-4">
        <div class="col-md-6 py-2">
            <div class="shadow rounded p-4">
                <h5 class=" py-1">Total Damage Reduce Count</h5>
                <div class="d-flex align-items-center gap-1">
                    <div class="shadow py-2 px-2 bg-primary rounded-circle d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-ticket fs-2 text-white"></i>
                    </div>
                    <h5 class="py-2">{{$damageReduce->total()}}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-6 py-2">
            <div class="shadow rounded p-4">
                <h5 class=" py-1">Total Damage Reduce Price</h5>
                <div class="d-flex align-items-center gap-1">
                    <div class="shadow py-2 px-3 bg-primary rounded-circle d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-dollar-sign fs-2 text-white"></i>
                    </div>
                    <h5 class="py-2">{{number_format($damageReduceTotalPrice)}}</h5>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="d-flex align-items-center justify-content-between">
        <form action="" id="search-form">
            <div class="search-cinema">
                <svg class="icon" aria-hidden="true" viewBox="0 0 24 24"><g><path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path></g></svg>
                <input placeholder="Search" type="search" name="search_key" value="{{request('search_key')}}" class="input">
            </div>
        </form>
    </div>
    <form action="" id="search-form-small" class="mb-3">
        <div class="search-cinema">
            <svg class="icon" aria-hidden="true" viewBox="0 0 24 24"><g><path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path></g></svg>
            <input placeholder="Search" type="search" name="search_key" value="{{request('search_key')}}" class="input">
        </div>
    </form>
        </form>
    <form method="get" action="" class="mt-2 bg-body-secondary rounded px-2">
        <div class="py-2 d-flex gap-1 flex-wrap py-2">
            <div>
                <select name="product" class="form-control" style="font-size: 11pt;">
                    <option value="">Filter By Product</option>
                    <option value="">All</option>
                    @foreach ($product as $item)
                        <option value="{{$item->product_name}}" @if ($item->product_name==request('product')) selected @endif>{{$item->product_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="d-flex align-items-center justify-content-between flex-wrap mt-1">
            <div class="d-flex gap-1 flex-wrap py-2">
                <div>
                    <label for="" style="font-size: 11pt;">Start Date</label>
                    <input type="date" style="font-size: 11pt;" name="start_date" class="form-control" value="{{request('start_date')}}">
                </div>
                <div>
                    <label for="" style="font-size: 11pt;">End Date</label>
                    <input type="date" style="font-size: 11pt;" name="end_date" class="form-control" value="{{request('end_date')}}">
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-end w-100 mb-2">
                <button class="btn btn-primary me-2" style="font-size: 11pt;">Filter</button>
                <a href="{{route('admin#reduceList')}}" style="font-size: 11pt;" class="btn btn-danger">Clear</a>
            </div>
        </div>
    </form> --}}
    <div class="p-2">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{session('success')}}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                {{session('warning')}}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('danger'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{session('danger')}}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger py-3 alert-dismissible fade show" role="alert">
                {{ $error }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endforeach
    @endif
    </div>
    <div class="">
        <div class="py-2">
            <div class="py-2">
                <h2>Reduce</h2>
            </div>
            <table id="table-list">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>QTY</th>
                        <th>Total Price</th>
                        <th>Reduce By</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($normalReduce as $item)
                    <tr>
                        <td>{{$item->id}}</td>
                        <td>{{$item->product_name}}</td>
                        <td>{{$item->qty}}</td>
                        <td>{{$item->total_price}} MMK</td>
                        <td>{{$item->user_name}}</td>
                        <td>{{$item->created_at}}</td>
                        <td>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete-reduce{{$item->id}}">Delete</button>
                        </td>
                        <div class="modal fade" id="delete-reduce{{$item->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="delete-productLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title d-block w-100 text-center text-danger" id="delete-productLabel">Delete Purchase</h4>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{route('admin#reduceDelete')}}" method="post" class="mt-1">
                                            @csrf
                                            @method('DELETE')
                                            <div class="py-1">
                                                <p class="text-danger">"Are you sure to delete this."</p>
                                            </div>
                                            <div class="py-1">
                                                <h6>Verify you are admin. Please enter the password.</h6>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label class="mb-1">Password</label>
                                                <input id="" name="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter your password." required>
                                                @error('password')
                                                    <small class="text-danger">{{$message}}</small>
                                                @enderror
                                            </div>

                                            <input type="hidden" name="id" value="{{$item->id}}">

                                            <button type="submit" class="btn btn-danger w-100 my-2">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div>
                {{$normalReduce->appends(request()->query())->links()}}
            </div>
        </div>
        <div class="py-2">
            <div class="py-2">
                <h2>Damage Reduce</h2>
            </div>
            <table id="table-list">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>QTY</th>
                        <th>Total Price</th>
                        <th>Reduce By</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($damageReduce as $item)
                    <tr>
                        <td>{{$item->id}}</td>
                        <td>{{$item->product_name}}</td>
                        <td>{{$item->qty}}</td>
                        <td>{{$item->total_price}} MMK</td>
                        <td>{{$item->user_name}}</td>
                        <td>{{$item->created_at}}</td>
                        <td>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete-reduce{{$item->id}}">Delete</button>
                        </td>
                        <div class="modal fade" id="delete-reduce{{$item->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="delete-productLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title d-block w-100 text-center text-danger" id="delete-productLabel">Delete Purchase</h4>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{route('admin#reduceDelete')}}" method="post" class="mt-1">
                                            @csrf
                                            @method('DELETE')
                                            <div class="py-1">
                                                <p class="text-danger">"Are you sure to delete this."</p>
                                            </div>
                                            <div class="py-1">
                                                <h6>Verify you are admin. Please enter the password.</h6>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label class="mb-1">Password</label>
                                                <input id="" name="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter your password." required>
                                                @error('password')
                                                    <small class="text-danger">{{$message}}</small>
                                                @enderror
                                            </div>

                                            <input type="hidden" name="id" value="{{$item->id}}">

                                            <button type="submit" class="btn btn-danger w-100 my-2">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div>
                {{$damageReduce->appends(request()->query())->links()}}
            </div>
        </div>
    </div>
</main>
@endsection
