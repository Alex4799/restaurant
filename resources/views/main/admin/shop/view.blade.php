@extends('main/admin/layout/master')
@section('title')
    Shop View
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
        <a href="{{route('admin#shopList')}}" class="text-decoration-none">List</a> >
        <span>View</span>
    </p>
    <div class="row ">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header text-center fs-5 fw-bold">
                    Shop View
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-4">
                            @if ($shop->image!=null)
                                <img src="{{asset('storage/shop/'.$shop->image)}}" class="d-block w-100 img-thumbnail bg-transparent" alt="Shop Image">
                            @else
                                <img src="{{asset('admin/image/default.jpg')}}"  class="d-block w-100 img-thumbnail bg-transparent" alt="Shop Image">
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h6>Name - {{$shop->name}}</h6>
                            <h6>Location - {{$shop->location}}</h6>
                            <h6>Contact -
                                <div>{!!$shop->contact!!}</div>
                            </h6>
                            <h6>Status -
                                @if ($shop->active==0)
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

    <div>
        <div class="d-flex justify-content-between mt-5">
            <p class="today-sale"><i class="fa-solid fa-parachute-box me-2"></i>Table</p>
            <div>
                <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#createTable">Create</button>
            </div>
        </div>
        <div>
            <table id="table-list2">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Capacity</th>
                        <th>Status</th>
                        <th>Active</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($table as $item)
                        <tr>
                            <td>{{$loop->index+1}}</td>
                            <td>{{$item->name}}</td>
                            <td>{{$item->capacity}}</td>
                            <td>
                                @if ($item->status==0)
                                    <span class="text-success">Avaliable</span>
                                @else
                                    <span class="text-warning">Not Avaliable</span>
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
                                <div class="d-flex align-items-center">
                                    <button class="btn text-primary fs-5 border-0" data-bs-toggle="modal" data-bs-target="#editTable{{$item->id}}"><i class="fa-regular fa-pen-to-square"></i></button>
                                    <button class="btn text-primary fs-5 border-0" data-bs-toggle="modal" data-bs-target="#deleteTable{{$item->id}}"><i class="fa-solid fa-trash"></i></button>
                                </div>
                            </td>
                            <div class="modal fade" id="deleteTable{{$item->id}}" tabindex="-1" aria-labelledby="generateBarcode{{$item->id}}Label" aria-hidden="true">
                                <div class="modal-dialog">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h1 class="modal-title fs-5 d-block w-100 text-center" id="generateBarcode{{$item->id}}Label">Delete Table</h1>
                                       <button type="button" class="close-btn border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{route('admin#tableDelete')}}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <p class=" text-danger">
                                                Are you sure to delete this table. Please enter your password to verify that you are admin.
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
                            <div class="modal fade" id="editTable{{$item->id}}" tabindex="-1" aria-labelledby="editTable{{$item->id}}Label" aria-hidden="true">
                                <div class="modal-dialog">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h1 class="modal-title fs-5 d-block w-100 text-center" id="editTable{{$item->id}}Label">Edit Table</h1>
                                       <button type="button" class="close-btn border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{route('admin#tableUpdate')}}" method="POST" class="mt-1">
                                            @csrf
                                            @method('PUT')
                                            <div class="mb-4">
                                                <label for="" class="mb-1 fw-semibold">Name</label>
                                                <input type="text" name="name" class="form-control" value="{{$item->name}}">
                                            </div>
                                            <div class="form-group mb-4">
                                                <label for="" class="mb-1 fw-semibold">Capacity</label>
                                                <input type="number" value="{{$item->capacity}}" name="capacity" class="form-control">
                                            </div>
                                            <div class=" form-group mb-4">
                                                <label class="mb-1 fw-semibold">Status</label>
                                                <div class="d-flex align-items-center">
                                                    <select name="status" id="" class="form-control">
                                                        <option value="0" @if ($item->status==0) selected @endif>Avaliable</option>
                                                        <option value="1" @if ($item->status==1) selected @endif>Not Avaliable</option>
                                                    </select>
                                                    <i class="fa-solid fa-chevron-down" id="method-drop-icon2"></i>
                                                </div>
                                            </div>
                                            <div class=" form-group mb-4">
                                                <label class="mb-1 fw-semibold">Active</label>
                                                <div class="d-flex align-items-center">
                                                    <select name="active" id="" class="form-control">
                                                        <option value="0" @if ($item->active==0) selected @endif>Not Active</option>
                                                        <option value="1" @if ($item->active==1) selected @endif>Active</option>
                                                    </select>
                                                    <i class="fa-solid fa-chevron-down" id="method-drop-icon2"></i>
                                                </div>
                                            </div>
                                            <input type="hidden" name="id" value="{{$item->id}}">
                                            <input type="hidden" name="shop_id" value="{{$shop->id}}">
                                            <button type="submit" class="btn add-btn w-100 my-2">Update</button>
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

    <div class="py-2">
        <div class="table-content">
            <div class="d-flex justify-content-between mt-5">
                <p class="today-sale"><i class="fa-solid fa-parachute-box me-2"></i>Store</p>
                <div>
                    @if (Auth::user()->position=='admin'||Auth::user()->position=='developer')
                        <button class="btn btn-outline-success" type="btn" data-bs-toggle="modal" data-bs-target="#add-store">Create</button>
                    @endif
                </div>
            </div>
            <div>
                <table id="table-list2">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Manager</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="py-4">
                        @foreach ($store as $item)
                            <tr>
                                <td>{{$item->id}}</td>
                                <td>{{$item->name}}</td>
                                <td>{{$item->manager_name}}</td>
                                <td>
                                    @if ($item->active==0)
                                        <span class="text-danger">Not Active</span>
                                    @else
                                        <span class="text-success">Active</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{route('admin#storeView',$item->id)}}" class="text-bg-primary px-2 py-1 rounded" title="view more">
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
</div>
<!-- Modal -->
<div class="modal fade" id="edit" tabindex="-1" aria-labelledby="editLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
          <h1 class="modal-title fs-5 d-block w-100 text-center" id="editLabel">Shop Edit</h1>
          <button type="button" class="close-btn border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <form action="{{route('admin#shopUpdate')}}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group mb-3">
                    <label class="mb-1 fw-semibold">Name</label>
                    <input id="" type="text" class="form-control" name="name" value="{{$shop->name}}" placeholder="Enter name">
                </div>
                <div class="form-group mb-4">
                    <label class="mb-2 fw-semibold">Image</label>
                    <div class="row">
                        <div class="col-6">
                            @if ($shop->image!=null)
                                <img src="{{asset('storage/shop/'.$shop->image)}}" class="d-block w-100 img-thumbnail bg-transparent" id="image" alt="Shop Image">
                            @else
                                <img src="{{asset('admin/image/default.jpg')}}"  class="d-block w-100 img-thumbnail bg-transparent" id="image" alt="Shop Image">
                            @endif
                        </div>
                        <div class="col-6 m-auto">
                            <input type="file" name="image" id="input_image" class="form-control" accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="form-group mb-4">
                    <label class="mb-1 fw-semibold">Location</label>
                    <input id="" type="text" class="form-control" name="location" value="{{$shop->location}}" placeholder="Enter location">
                </div>
                <div class="form-group mb-4">
                    <label class="mb-1 fw-semibold">Contact</label>
                    <textarea id="editor" class="form-control editor" name="contact" cols="30" rows="10">{!!$shop->contact!!}</textarea>
                </div>
                <div class=" form-group mb-4">
                    <label class="mb-1 fw-semibold">Status</label>
                    <div class="d-flex align-items-center">
                        <select name="active" id="" class="form-control">
                            <option value="0" @if ($shop->active==0) selected @endif>Not Active</option>
                            <option value="1" @if ($shop->active==1) selected @endif>Active</option>
                        </select>
                        <i class="fa-solid fa-chevron-down" id="method-drop-icon2"></i>
                    </div>
                </div>
                <input type="hidden" name="id" value="{{$shop->id}}">
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
          <h1 class="modal-title fs-5 d-block w-100 text-center" id="deleteLabel">Shop Delete</h1>
          <button type="button" class="close-btn border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <form action="{{route('admin#shopDelete')}}" method="post">
                @csrf
                @method('DELETE')
                <p class=" text-danger">
                    Are you sure to delete this shop. Please enter your password to verify that you are admin.
                </p>
                <div class="form-group mb-3">
                    <label class="mb-1 fw-semibold">Password</label>
                    <input id="" type="password" class="form-control" name="password" placeholder="Enter your password">
                </div>

                <input type="hidden" name="id" value="{{$shop->id}}">

                <button class="btn btn-danger w-100 border-0">Delete</button>
            </form>
        </div>
      </div>
    </div>
</div>
<div class="modal fade" id="createTable" tabindex="-1" aria-labelledby="createTableLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5 d-block w-100 text-center" id="createTableLabel">Create Table</h1>
            <button type="button" class="close-btn border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <form action="{{route('admin#tableCreate')}}" method="POST" class="mt-1">
                @csrf
                <div class="mb-4">
                    <label for="" class="mb-1 fw-semibold">Name</label>
                    <input type="text" name="name" class="form-control">
                </div>
                <div class="form-group mb-4">
                    <label for="" class="mb-1 fw-semibold">Capacity</label>
                    <input type="number" name="capacity" class="form-control">
                </div>
                <input type="hidden" name="shop_id" value="{{$shop->id}}">
                <button type="submit" class="btn add-btn w-100 my-2">Create</button>
            </form>

        </div>
        </div>
    </div>
</div>
<div class="modal fade" id="add-store" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="add-storeLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
                <div class="modal-header">
                <h1 class="modal-title fs-5 d-block w-100 text-center" id="add-storeLabel">Store Create</h1>
                <button type="button" class="close-btn border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <form action="{{route('admin#storeCreate')}}" method="POST" class="mt-1">
                    @csrf
                    <div class="form-group mb-4">
                        <label for="" class="mb-1 fw-semibold">Name</label>
                        <input type="text" name="name" class="form-control">
                    </div>
                    <div class="form-group mb-4">
                        <label for="" class="mb-1 fw-semibold">Manager</label>
                        <div class="d-flex align-items-center">
                            <select name="manager_id" class="form-control">
                                <option value="">Choose Manager Account</option>
                                @foreach ($manager as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                            <i class="fa-solid fa-chevron-down" id="method-drop-icon2"></i>
                        </div>
                    </div>
                    <div class="form-group mb-4">
                        <label for="" class="mb-1 fw-semibold">Contact</label>
                        <textarea class="editor" name="contact"></textarea>
                    </div>
                    <div class="form-group mb-4">
                        <label for="" class="mb-1 fw-semibold">Address</label>
                        <textarea name="address" class="form-control" cols="30" rows="5"></textarea>
                    </div>

                    <input type="hidden" name="shop_id" value="{{$shop->id}}">
                    <button type="submit" class="btn add-btn w-100 my-2">Create</button>
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
