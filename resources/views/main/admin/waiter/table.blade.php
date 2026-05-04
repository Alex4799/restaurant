@extends('main/admin/layout/master')
@section('title')
    Table List
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
                    <span>All ( {{count($table)}} )</span>
                </div>
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
                        <th>Capacity</th>
                        <th>Order</th>
                        <th>Kitchen Finished Order</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($table as $item)
                        <tr id="{{$item->name}}">
                            <td>{{$loop->index+1}}</td>
                            <td>{{$item->name}}</td>
                            <td>{{$item->capacity}}</td>
                            <td>{{$item->order}}</td>
                            <td class="kitchen_finished_order">{{$item->kitchen_finished_order}}</td>
                            <td class="status">
                                @if ($item->status==0)
                                    <span class="text-success">Avaliable</span>
                                @else
                                    <span class="text-warning">Not Avaliable</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{route('admin#tableViewWaiter',$item->id)}}" class="text-bg-primary px-2 py-1 rounded" title="view more">
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
@endsection
@section('script')

@endsection
