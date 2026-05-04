@extends('main/admin/layout/master')
@section('title')
    Order List
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
        <div class="d-flex align-items-center justify-content-between table-title">
            <div class="d-flex align-items-center">
                <div class="py-1 px-3">
                    <span>All ( {{count($order)}} )</span>
                </div>
            </div>
            <form action="" id="search-table">
                <div class="d-flex align-items-center">
                    <div>
                        <span>OID</span>
                    </div>
                    <div class="group">
                        <svg class="icon" aria-hidden="true" viewBox="0 0 24 24"><g><path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path></g></svg>
                        <input placeholder="Search by order ID ..." type="search" name="search_key" value="{{request('search_key')}}" class="input">
                    </div>
                </div>
            </form>
        </div>
        <div class="d-flex align-items-center justify-content-end mt-2">
            <div class="d-flex align-items-center">
                <div class="dropdown">
                    <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        @if (request('status')==='0')
                            <span class="text-warning">Pending</span>
                        @elseif(request('status')=='1')
                            <span class="text-success">Success</span>
                        @elseif(request('status')=='2')
                            <span class="text-danger">Reject</span>
                        @else
                            <span>All</span>
                        @endif
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{route('admin#orderList')}}">All</a></li>
                        <li><a class="dropdown-item" href="{{route('admin#orderList',['status'=>'0'])}}">Pending</a></li>
                        <li><a class="dropdown-item" href="{{route('admin#orderList',['status'=>'1'])}}">Success</a></li>
                        <li><a class="dropdown-item" href="{{route('admin#orderList',['status'=>'2'])}}">Reject</a></li>
                    </ul>
                </div>
                {{-- <div class="dropdown ms-3">
                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Filter By Mode
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">All</a></li>
                        <li><a class="dropdown-item" href="#">Online Mode</a></li>
                        <li><a class="dropdown-item" href="#">Offline Mode</a></li>
                    </ul>
                </div> --}}
            </div>
        </div>
        <div>
            <table id="table-list2">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Shop Name</th>
                        <th>Seller Name</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="py-4">
                    @foreach ($order as $item)
                        <tr>
                            <td>
                                <a href="{{route('admin#orderSummary',$item->id)}}" class="text-decoration-none">OID - {{$item->id}}</a>
                            </td>
                            <td>{{$item->shop_name}}</td>
                            <td>{{$item->seller_name}}</td>
                            <td>{{$item->total_price}} {{$item->currency}}</td>
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
                                <a href="{{route('admin#orderSummary',$item->id)}}" class="text-bg-primary px-2 py-1 rounded" title="view more">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div>
                {{$order->appends(request()->query())->links()}}
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')

@endsection
