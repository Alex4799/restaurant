@extends('main.admin.layout.master')
@section('title')
    Product List
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
                    <span>All ( {{count($product)}} )</span>
                </div>
                <button data-bs-toggle="modal" data-bs-target="#create">Create</button>
            </div>
            <form action="" id="search-table">
                <div class="group">
                    <svg class="icon" aria-hidden="true" viewBox="0 0 24 24"><g><path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path></g></svg>
                    <input placeholder="Search by name ..." name="search_key" value="{{request('search_key')}}" type="search" class="input">
                </div>
            </form>
        </div>
        <div class="d-flex align-items-center justify-content-between flex-wrap my-2">
            <div class="item-per mb-2">
                <div class="p-2">Item per page</div>
                <form action="" class="d-flex">
                    <select name="pagination" class="form-control" id="">
                        <option value="25" @if ($paginate=='25') selected @endif>25</option>
                        <option value="50" @if ($paginate=='50') selected @endif>50</option>
                        <option value="75" @if ($paginate=='75') selected @endif>75</option>
                        <option value="100" @if ($paginate=='100') selected @endif>100</option>
                    </select>
                    <div>
                        <button class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
            <div>
                <form action="" id="filter-content">
                    <div class="d-flex align-items-center w-100 me-4">
                        <select name="category" id="" class="form-control">
                            <option value="">Select Catagory</option>
                            <option value="">All</option>
                            @foreach ($category as $item)
                                <option value="{{$item->id}}" @if ($item->id==request('category')) selected @endif>{{$item->name}}</option>
                            @endforeach
                        </select>
                        <i class="fa-solid fa-chevron-down" id="method-drop-icon2"></i>
                    </div>
                    <div class="d-flex align-items-center w-100">
                        <select name="active" id="" class="form-control">
                            <option value="">Filter By Status</option>
                            <option value="">All</option>
                            <option value="0" @if (request('active')==='0') selected @endif>Not Active</option>
                            <option value="1" @if (request('active')==1) selected @endif>Active</option>
                        </select>
                        <i class="fa-solid fa-chevron-down" id="method-drop-icon2"></i>
                    </div>
                    <button class="btn btn-success" id="filter">Filter</button>
                </form>
            </div>
        </div>
        <div>
            <table id="table-list">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($product as $item)
                        <tr>
                            <td>
                                @if ($item->image!=null)
                                    <img src="{{asset('storage/product/'.$item->image)}}" class="img-thumbnail" alt="">
                                @else
                                    <div style="background: {{$item->default_image}};width:50px;height:50px">
                                    </div>
                                @endif
                            </td>
                            <td class="t-list-column">
                                <span>{{$item->name}}</span>
                                <div class="table-action">
                                    <span>ID:{{$item->id}}</span> |
                                    <a href="{{route('admin#productView',$item->id)}}">View</a>
                                </div>
                            </td>
                            <td>{{$item->category_name}}</td>
                             <td class="t-list-column2">
                                {!! Str::limit(preg_replace('/\s*<br\s*\/?>\s*|\s*<\/?p>\s*/', ' ', $item->description), 100, '...') !!}
                            </td>
                            <td class="">
                                @if ($item->active==0)
                                    <span class="text-danger">Not Active</span>
                                @else
                                    <span class="text-success">Active</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div>
                {{$product->appends(request()->query())->links()}}
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="create" tabindex="-1" aria-labelledby="createLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5 d-block w-100 text-center" id="createLabel">Product Create</h1>
          <button type="button" class="close-btn border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <form action="{{route('admin#productCreate')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group mb-3">
                    <label class="mb-1 fw-semibold">Name</label>
                    <input id="" value="{{old('name')}}" type="text" class="form-control" name="name" placeholder="Enter name">
                </div>
                <div class="form-group mb-4">
                    <label class="mb-2 fw-semibold">Image</label>
                    <div class="row">
                        <div class="col-6">
                            <img src="{{asset('admin/image/default.jpg')}}" id="image" class="w-75 mx-auto d-block img-thumbnail" alt="">
                        </div>
                        <div class="col-6 m-auto">
                            <input type="file" name="image" id="input_image" class="form-control" accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="form-group mb-4">
                    <label class="mb-1 fw-semibold">Category</label>
                    <div class="d-flex align-items-center w-100">
                        <select name="category" id="" class="form-control">
                            <option value="">Select Catagory</option>
                            @foreach ($category as $item)
                                <option value="{{$item->id}}" @if (old('category')==$item->id) selected @endif>{{$item->name}}</option>
                            @endforeach
                        </select>
                        <i class="fa-solid fa-chevron-down" id="method-drop-icon2"></i>
                    </div>
                </div>
                <div class="form-group mb-4">
                    <label class="mb-1 fw-semibold">Description</label>
                    <textarea id="editor" class="form-control" name="description" cols="30" rows="10">{{old('description')}}</textarea>
                </div>
                <div class="form-group mb-4">
                    <div>
                        <label class="mb-1">Default Image Color</label>
                    </div>
                    <input id="" name="default_image" type="color" value="#00ff59">
                </div>
                <button class="btn w-100 border-0" id="update-btn" >Create</button>
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

            ClassicEditor
                .create( document.querySelector( '#editor' ), {
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
        </script>
@endsection
