@extends('main/admin/layout/master')
@section('title')
    Supplier List
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

        <!--supplier list start  -->
        <div class="table-container">
            <div class="table-content">
                <div class="table-title">
                    <div class="d-flex align-items-center">
                        <div class="py-1 px-3">
                            <span>All ( {{count($supplier)}} )</span>
                        </div>
                        <button data-bs-toggle="modal" data-bs-target="#add-supplier">Create</button>
                    </div>
                    <form action="" id="search-table">
                        <div class="group">
                            <svg class="icon" aria-hidden="true" viewBox="0 0 24 24"><g><path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path></g></svg>
                            <input placeholder="Search by name ..." name="search_key" value="{{request('search_key')}}" type="search" class="input">
                        </div>
                    </form>
                </div>
                <div>
                    <table id="table-list">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="py-4">
                            @foreach ($supplier as $item)
                                <tr>
                                    <td>{{$item->id}}</td>
                                    <td>{{$item->name}}</td>
                                    <td class="pt-3">{!!$item->contact!!}</td>
                                    <td class="pt-3">{!!$item->address!!}</td>
                                    <td>
                                        <a href="" data-bs-toggle="modal" data-bs-target="#edit{{$item->id}}" class="text-decoration-none">Edit</a> |
                                        <a href="" data-bs-toggle="modal" data-bs-target="#delete{{$item->id}}" class="text-danger text-decoration-none" >Delete</a>
                                    </td>
                                    <div class="modal fade" id="edit{{$item->id}}" tabindex="-1" aria-labelledby="edit{{$item->id}}Label" aria-hidden="true">
                                        <div class="modal-dialog">
                                          <div class="modal-content">
                                            <div class="modal-header">
                                              <h1 class="modal-title fs-5 d-block w-100 text-center" id="edit{{$item->id}}Label">Supplier Edit</h1>
                                              <button type="button" class="close-btn border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{route('admin#supplierUpdate')}}" method="POST" class="mt-1">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="form-group mb-4">
                                                        <label for="" class="mb-1 fw-semibold">Supplier Name</label>
                                                        <input type="text" value="{{$item->name}}" name="name" class="form-control">
                                                    </div>
                                                    <div class="form-group mb-4">
                                                        <label for="" class="mb-1 fw-semibold">Contact</label>
                                                        <textarea class="editor" name="contact">{!!$item->contact!!}</textarea>
                                                    </div>
                                                    <div class="form-group mb-4">
                                                        <label for="" class="mb-1 fw-semibold">Address</label>
                                                        <textarea name="address" id="" class="editor">{!!$item->address!!}</textarea>
                                                    </div>
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
                                              <h1 class="modal-title fs-5 d-block w-100 text-center" id="delete{{$item->id}}Label">Supplier Delete</h1>
                                              <button type="button" class="close-btn border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{route('admin#supplierDelete')}}" method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <p class=" text-danger">
                                                        Are you sure to delete this supplier. Please enter your password to verify that you are admin.
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
        <!--supplier list end  -->

        <!-- supplier create modal -->
        <div class="modal fade" id="add-supplier" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="add-supplierLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5 d-block w-100 text-center" id="add-supplierLabel">Supplier Create</h1>
                        <button type="button" class="close-btn border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('admin#supplierCreate')}}" method="POST" class="mt-1">
                            @csrf
                            <div class="form-group mb-4">
                                <label for="" class="mb-1 fw-semibold">Supplier Name</label>
                                <input type="text" name="name" class="form-control">
                            </div>
                            <div class="form-group mb-4">
                                <label for="" class="mb-1 fw-semibold">Contact</label>
                                <textarea class="editor" name="contact"></textarea>
                            </div>
                            <div class="form-group mb-4">
                                <label for="" class="mb-1 fw-semibold">Address</label>
                                <textarea name="address" id="" class="editor" cols="30" rows="10"></textarea>
                            </div>
                            <button type="submit" class="btn add-btn w-100 my-2">Add</button>
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
