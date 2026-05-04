@extends('main/admin/layout/master')
@section('title')
    Order Summary
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
        <a href="{{route('admin#orderList')}}" class="text-decoration-none">Order List</a> >
        <span>View</span>
    </p>
    <div class="row container-lg mt-4 mx-auto">
        <div class="col-lg-4 col-md-12 mb-5">
            <div class="">
                <h6><i class="fa-solid fa-cart-shopping me-2"></i>Order Details</h6>
                <div class="view-order-detail">
                    <div>
                        <span class="d-flex align-items-center"><i class="fa-solid fa-info me-3"></i>OID - {{$order->id}}</span>
                    </div>
                    @foreach ($paymentMethod as $item)
                        <div>
                            <span class="d-flex align-items-center"><i class="fa-regular fa-credit-card me-3"></i> {{$item['method']}} - {{$item['amount']}} {{$order->currency}}</span>
                        </div>
                    @endforeach
                    <div>
                        <span class="d-flex align-items-center"><i class="fa-solid fa-calendar-days me-3"></i> {{$order->table}}</span>
                    </div>
                    <div>
                        <span class="d-flex align-items-center"><i class="fa-solid fa-calendar-days me-3"></i> {{$order->created_at}}</span>
                    </div>
                </div>
            </div>
        </div>
        @if ($order->user_name!=null)
            <div class="col-lg-4 col-md-12 mb-5">
                <div class="">
                    <h6><i class="fa-solid fa-address-card me-2"></i>Customer Details</h6>
                    <div class="view-order-detail">
                        <div>
                            <span class="d-flex align-items-center"><i class="fa-regular fa-user me-3"></i> {{$order->user_name}}</span>
                        </div>
                        <div>
                            <span class="d-flex align-items-center"><i class="fa-regular fa-envelope me-3"></i>{{$order->user_email}}</span>
                        </div>
                        <div>
                            <span class="d-flex align-items-center"><i class="fa-solid fa-mobile-screen me-3"></i>{{$order->user_phone}}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="col-lg-4 col-md-12">
            <div class="">
                <h6><i class="fa-solid fa-money-check-dollar me-2"></i>Amount Details</h6>
                <div class="view-order-detail">
                    <div>
                        <span class="d-flex align-items-center">
                            <span>Total Price -
                                <span class="text-success fw-bold ms-2">{{$order->subtotal}}</span> {{$order->currency}}
                            </span>
                        </span>
                    </div>
                    <div>
                        <span class="d-flex align-items-center">
                            <span>Pay Amount - {{$order->pay_amount}} {{$order->currency}}</span>
                        </span>
                    </div>
                    <div>
                        <span class="d-flex align-items-center">
                            <span>Pay Left - {{$order->pay_left}} {{$order->currency}}</span>
                        </span>
                    </div>
                    <div>
                        <span class="d-flex align-items-center">
                            <span>Change - {{$order->change}} {{$order->currency}}</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="status-content">
        <table class="t-store-details">
            <thead>
                <tr>
                    <th>Shop Name</th>
                    <th>Seller Name</th>
                    <th>Order Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{$order->shop_name}}</td>
                    <td>{{$order->seller_name}}</td>
                    <td>
                        @if ($order->status==0)
                            <span class="text-warning">Pending</span>
                        @elseif($order->status==1)
                            <span class="text-success">Success</span>
                        @elseif($order->status==2)
                            <span class="text-danger">Reject</span>
                        @elseif($order->status==3)
                            <span class="text-warning">Delivered</span>
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
        <div>
            <div class="d-flex align-items-center mt-5">
                <span class="me-2">Status: </span>
                @if ($order->status!=2)
                    <div class="dropdown">
                        <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            @if ($order->status==0)
                                <span class="text-warning">Pending</span>
                            @elseif($order->status==1)
                                <span class="text-success">Success</span>
                            @elseif($order->status==2)
                                <span class="text-danger">Reject</span>
                            @elseif($order->status==3)
                                <span class="text-warning">Delivered</span>
                            @endif
                        </button>
                        <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Pending</a></li>
                        <li><a class="dropdown-item" href="#">Deliveried</a></li>
                        <li><a class="dropdown-item" href="#">Success</a></li>
                        <li><a class="dropdown-item" href="#">Reject</a></li>
                        </ul>
                    </div>
                @else
                    <button class="btn btn-danger">Reject</button>
                @endif
            </div>
        </div>
    </div>
    <div>
        <table id="order-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Quantity</th>
                    <th>Total ({{$order->currency}})</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orderItem as $item)
                    <tr>
                        <td>
                            <img src="{{asset('storage/product/'.$item->image)}}" alt="">
                        </td>
                        <td>{{$item->product_name}}</td>
                        <td>{{$item->category_name}}</td>
                        <td>{{$item->qty}}</td>
                        <td class="text-end">{{$item->price*$item->qty}}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4">Subtotal</td>
                    <td colspan="1" class="text-end">{{$order->total_price}}</td>
                </tr>
                <tr>
                    <td colspan="4">Tax</td>
                    <td colspan="1" class="text-end">{{$order->tax_price}}</td>
                </tr>
                <tr>
                    <td colspan="4">Discount</td>
                    <td colspan="1" class="text-end">{{$order->promotion_price}}</td>
                </tr>
                <tr>
                    <td colspan="4">Total Price</td>
                    <td colspan="1" class="text-end text-success fw-bold">{{$order->subtotal}}</td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="d-flex align-items-center justify-content-end mt-3 gap-1">
        @if ($order->status==1)
            <a href="{{route('admin#orderGenerateInvoice',$order->id)}}" target="_blank" class="btn btn-success">Generate Invoice</a>
        @endif
        @if ($order->status==0)
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#payment">
                Finish Payment
            </button>
        @endif
    </div>
    <div class="modal fade" id="payment" tabindex="-1" aria-labelledby="paymentLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="paymentLabel">Finish Payment</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form action="{{route('admin#orderFinish')}}" method="post">
                @csrf
                <div class="py-2">
                    <label for="">Pay Left</label>
                    <input type="number" name="payleft" value="{{$order->pay_left}}" id="" class="form-control" readonly>
                </div>
                <div class="d-flex justify-content-between py-2">
                    <div>
                        <label for="">Pay Amount</label>
                        <input type="number" name="pay_amount" class="form-control">
                    </div>
                    <div>
                        <label for="">Payment Method</label>
                        <select name="payment_method" id="" class="form-control">
                            <option value="">Choose Payment Method</option>
                            @foreach ($payment as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <input type="hidden" name="id" value="{{$order->id}}">
                <div class="py-2 d-flex justify-content-end">
                    <button class="btn btn-success">Pay</button>
                </div>
              </form>
            </div>
          </div>
        </div>
    </div>
</div>
@endsection
@section('script')

@endsection
