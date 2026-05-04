@extends('main/admin/layout/master')
@section('title')
    Kitchen
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

    <div id="alert"></div>
</div>

<div class="table-container">
    <div class="table-content">
        <div class="d-flex align-items-center justify-content-between table-title">
            <div class="d-flex align-items-center">
                <div class="py-1 px-3">
                    <span>All ( {{count($list)}} )</span>
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
        <div class="d-flex align-items-center justify-content-end mt-2 gap-1">
            <div class="d-flex align-items-center">
                <div class="dropdown">
                    <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        @if (request('status')==='0')
                            <span class="text-warning">Pending</span>
                        @elseif(request('status')=='1')
                            <span class="text-warning">Cooking</span>
                        @elseif(request('status')=='2')
                            <span class="text-success">Finished</span>
                        @elseif(request('status')=='3')
                            <span class="text-success">Received</span>
                        @elseif(request('status')=='4')
                            <span class="text-danger">Reject</span>
                        @else
                            <span>All</span>
                        @endif
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{route('admin#kitchenList',['today'=>request('today')])}}">All</a></li>
                        <li><a class="dropdown-item" href="{{route('admin#kitchenList',['status'=>'0','today'=>request('today')])}}">Pending</a></li>
                        <li><a class="dropdown-item" href="{{route('admin#kitchenList',['status'=>'1','today'=>request('today')])}}">Cooking</a></li>
                        <li><a class="dropdown-item" href="{{route('admin#kitchenList',['status'=>'2','today'=>request('today')])}}">Finished</a></li>
                        <li><a class="dropdown-item" href="{{route('admin#kitchenList',['status'=>'3','today'=>request('today')])}}">Received</a></li>
                        <li><a class="dropdown-item" href="{{route('admin#kitchenList',['status'=>'4','today'=>request('today')])}}">Reject</a></li>
                    </ul>
                </div>
            </div>
            <div>
                @if (request('today'))
                    <a href="{{route('admin#kitchenList',['status'=>request('status')])}}" class="btn btn-outline-primary">All Day</a>
                @else
                    <a href="{{route('admin#kitchenList',['status'=>request('status'),'today'=>'true'])}}" class="btn btn-outline-primary">Today Only</a>
                @endif
            </div>
        </div>
        <div>
            <table id="table-list2">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Order ID</th>
                        <th>Product Name</th>
                        <th>Qty</th>
                        <th>Table</th>
                        <th>Note</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody class="py-4">
                    @foreach ($list as $item)
                        <tr class="@if ($item->status==0) pending @endif">
                            <td>{{$item->id}}</td>
                            <td>OID - {{$item->order_id}}</td>
                            <td>{{$item->product_name}}</td>
                            <td>{{$item->qty}}</td>
                            <td>{{$item->table}}</td>
                            <td>{{$item->note}}</td>
                            <td>
                                @if ($item->status==3)
                                    <button class="btn btn-outline-success">Received</button>
                                @elseif ($item->status==4)
                                    <button class="btn btn-outline-danger">Reject</button>
                                @else
                                    <div class="dropdown">
                                        <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            @if ($item->status==0)
                                                <span class="text-warning">Pending</span>
                                            @elseif($item->status==1)
                                                <span class="text-warning">Cooking</span>
                                            @elseif($item->status==2)
                                                <span class="text-success">Finished</span>
                                            @elseif($item->status==3)
                                                <span class="text-success">Received</span>
                                            @else
                                                <span class="text-danger">Reject</span>
                                            @endif
                                        </button>
                                        <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{route('admin#changeStatus',[$item->id,0])}}">Pending</a></li>
                                        <li><a class="dropdown-item" href="{{route('admin#changeStatus',[$item->id,1])}}">Cooking</a></li>
                                        <li><a class="dropdown-item" href="{{route('admin#changeStatus',[$item->id,2])}}">Finished</a></li>
                                        <li><a class="dropdown-item" href="{{route('admin#changeStatus',[$item->id,3])}}">Received</a></li>
                                        <li><a class="dropdown-item" href="{{route('admin#changeStatus',[$item->id,4])}}">Reject</a></li>
                                        </ul>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div>
                {{$list->appends(request()->query())->links()}}
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    Pusher.logToConsole = false;

    var pusher = new Pusher('6f1ebfd4aa55bf62ed9a', {
        cluster: 'ap1'
    });
    var channel = pusher.subscribe('kitchen-alert-channel');
    channel.bind('kitchen-alert-event', function(data) {
        console.log(data);
        if (data.shop_id==shopId) {
            let order=data.order;
            temp=`
                <tr class="${order.status==0? 'pending' : ''}">
                    <td>${order.id}</td>
                    <td>OID - ${order.order_id}</td>
                    <td>${order.product_name}</td>
                    <td>${order.qty}</td>
                    <td>${order.table}</td>
                    <td>${order.note??''}</td>
                    <td>${renderButton(order.status,order.id)}</td>
                </tr>
            `;
            addNewRow(temp);
            showAlert(order);
        }
    });

    function addNewRow(rowHtml) {
        let lastPending = $('#table-list2 tbody tr.pending').last();
        if (lastPending.length > 0) {
            lastPending.after(rowHtml); // insert after last pending
        } else {
            $('#table-list2 tbody').append(rowHtml); // no pending → normal append
        }
    }

    function renderButton(status,id){
        let changeStatusUrl = "{{ url('admin/kitchen/change/status') }}";
        if (status==3) {
            return `<button class="btn btn-outline-success">Received</button>`;
        }else if(status==4){
            return `<button class="btn btn-outline-danger">Reject</button>`;
        }else{
            return `
                <div class="dropdown">
                    <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        ${status == 0 ? '<span class="text-warning">Pending</span>' : ''}
                        ${status == 1 ? '<span class="text-warning">Cooking</span>' : ''}
                        ${status == 2 ? '<span class="text-success">Finished</span>' : ''}
                        ${status == 3 ? '<span class="text-success">Received</span>' : ''}
                        ${status == 4 ? '<span class="text-danger">Reject</span>' : ''}
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="${changeStatusUrl}/${id}/0">Pending</a></li>
                        <li><a class="dropdown-item" href="${changeStatusUrl}/${id}/1">Cooking</a></li>
                        <li><a class="dropdown-item" href="${changeStatusUrl}/${id}/2">Finished</a></li>
                        <li><a class="dropdown-item" href="${changeStatusUrl}/${id}/3">Received</a></li>
                        <li><a class="dropdown-item" href="${changeStatusUrl}/${id}/4">Reject</a></li>
                    </ul>
                </div>
            `
        }
    }

    function showAlert(order){
        data=`
                <div class="alert alert-warning alert-dismissible fade show" style="font-size:11pt;" role="alert">
                    New order ID - ${order.id}. New product "${order.product_name}"
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
        `;
        $('#alert').html(data);
    }

</script>
@endsection
