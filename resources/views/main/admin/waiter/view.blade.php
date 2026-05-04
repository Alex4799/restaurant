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
        <a href="{{route('admin#tableListWaiter')}}" class="text-decoration-none">List</a> >
        <span>View</span>
    </p>
    <div class="row ">
        <div class="col-md-8 offset-md-2">
            <div class="card w-100">
                <div class="card-header text-center fs-5 fw-bold">
                    Table View
                </div>
                <div class="card-body">
                    <h6>Name - {{$table->name}}</h6>
                    <h6>Capacity - {{$table->capacity}}</h6>
                    <h6>Status -
                        @if ($table->status==0)
                            <span class="text-success">Avaliable</span>
                        @else
                            <span class="text-warning">Not Avaliable</span>
                        @endif
                    </h6>
                    @isset($order)
                        <div class="d-flex justify-content-end gap-1">
                            <button type="button" data-bs-toggle="modal" data-bs-target="#payment" class="btn btn-success">Finished Payment</button>
                        </div>
                    @endisset
                </div>
            </div>
        </div>
    </div>
    @isset($order)
        <div>
            <div>
                <div class="d-flex justify-content-between mt-5">
                    <p class="today-sale"><i class="fa-solid fa-parachute-box me-2"></i>OID - {{$order->id}}</p>
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
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order['orderItem'] as $orderItem)
                                <tr>
                                    <td>
                                        <img src="{{asset('storage/product/'.$orderItem->image)}}" alt="">
                                    </td>
                                    <td>{{$orderItem->product_name}}</td>
                                    <td>{{$orderItem->category_name}}</td>
                                    <td>{{$orderItem->qty}}</td>
                                    <td class="text-end">{{$orderItem->price*$orderItem->qty}}</td>
                                    <td>
                                        @if ($orderItem->status==0)
                                            <span class="text-warning">Pending</span>
                                        @elseif($orderItem->status==1)
                                            <span class="text-warning">Cooking</span>
                                        @elseif($orderItem->status==2)
                                            <span class="text-success">Finished</span>
                                        @elseif($orderItem->status==3)
                                            <span class="text-success">Received</span>
                                        @else
                                            <span class="text-danger">Reject</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($orderItem->status==2)
                                            <a href="{{route('admin#changeReceiveItem',$orderItem->id)}}" class="btn btn-outline-success">Receive</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4">Subtotal</td>
                                <td colspan="3" class="text-end">{{$order->total_price}}</td>
                            </tr>
                            <tr>
                                <td colspan="4">Tax</td>
                                <td colspan="3" class="text-end" id="taxPrice">{{$order->tax_price}}</td>
                            </tr>
                            <tr>
                                <td colspan="4">Discount</td>
                                <td colspan="3" class="text-end" id="promotionPrice">{{$order->promotion_price}}</td>
                            </tr>
                            <tr>
                                <td colspan="4">Total Price</td>
                                <td colspan="3" class="text-end text-success fw-bold" id="subTotal">{{$order->subtotal}}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    @endisset
</div>

{{-- modal  --}}

    @isset($order)
        <div class="modal fade" id="payment" tabindex="-1" aria-labelledby="paymentLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h1 class="modal-title fs-5" id="paymentLabel">Finish Payment</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <form action="{{route('admin#tableFinishOrder')}}" method="post">
                    @csrf
                    <div class="py-2">
                        <label for="">Pay Left</label>
                        <input type="number" name="payleft" value="{{$order->pay_left}}" id="payleft" class="form-control" readonly>
                    </div>
                    <div class="mt-1">
                        <div class="form-group mb-3">
                            <label class="mb-1">Tax</label>
                            <div class="d-flex align-items-center">
                                <select id="tax" name="tax_id" class="form-control">
                                    <option value="">Choose tax</option>
                                    @foreach ($tax as $item)
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                                <i class="fa-solid fa-chevron-down" id="method-drop-icon2"></i>
                            </div>
                        </div>
                        <div class="form-group mb-3 tax_price_container d-none">
                            <label class="mb-1">Tax Price</label>
                            <input type="number" name="tax_price" class="form-control tax_price" readonly>
                        </div>
                    </div>
                    <div class="mt-1">
                        <div class="form-group mb-3">
                            <label class="mb-1">Promotion</label>
                            <div class="d-flex align-items-center">
                                <select id="promotion" name="promotion_id" class="form-control">
                                    <option value="">Choose Promotion</option>
                                    @foreach ($promotion as $item)
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                    <option value="0">Custom</option>
                                </select>
                                <i class="fa-solid fa-chevron-down" id="method-drop-icon2"></i>
                            </div>
                        </div>
                        <div class="form-group mb-3 promotion_price_container d-none">
                            <label class="mb-1">Promotion Price</label>
                            <input type="number" name="promotion_price" class="form-control promotion_price">
                        </div>
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
                    <input type="hidden" name="table" value="{{$table->id}}">
                    <input type="hidden" name="tax_id" id="tax_id">
                    <input type="hidden" name="tax_price" id="tax_price">
                    <div class="py-2 d-flex justify-content-end">
                        <button class="btn btn-success">Pay</button>
                    </div>
                </form>
                </div>
            </div>
            </div>
        </div>
    @endisset

@endsection
@section('script')
    <script>
        let totalPrice={{$order->total_price??0}};
        let subTotal=0;
        let tax_id=null;
        let taxPrice=0;
        let promotionPrice=0;
        let promotion_id=null;

        $('#tax').change(function(){
            tax_id=$('#tax').val();
            if (tax_id!='') {
                $.ajax({
                        type: 'get',
                        url: `{{route('admin#texCheck')}}`,
                        data: { id: tax_id },
                        dataType: 'json',
                        success: function(data) {
                            if (data.amount != null) {
                                taxPrice = data.amount;
                            } else {
                                taxPrice = totalPrice * (data.percentage / 100);
                            }
                            $('#taxPrice').html(taxPrice);
                            $('.tax_price_container').removeClass('d-none');
                            $('.tax_price').val(taxPrice);
                            calculate();
                        }
                    });
            }
        })

        $('#promotion').change(function(){
            promotion_id=$(this).val();
            if (promotion_id=='') {
                $('.promotion_price_container').addClass('d-none');
            }else if(promotion_id==0){
                $('.promotion_price_container').removeClass('d-none');
                $('.promotion_price').prop('readonly',false);
            }else{
                $.ajax({
                        type: 'get',
                        url: `{{route('admin#promotionCheck')}}`,
                        data: { id: promotion_id },
                        dataType: 'json',
                        success: function(data) {
                            if (data.amount != null) {
                                promotionPrice = data.amount;
                            } else {
                                promotionPrice = totalPrice * (data.percentage / 100);
                            }
                            console.log(promotionPrice);

                            $('#promotionPrice').html(promotionPrice);
                            $('.promotion_price_container').removeClass('d-none');
                            $('.promotion_price').val(promotionPrice);
                            $('.promotion_price').prop('readonly',true);
                            calculate();
                        }
                });
            }
        })

        $(document).on("change",".promotion_price",function(){
            promotionPrice=$('.promotion_price').val();
            $('#promotionPrice').html(promotionPrice);
            calculate();
        })

        // $('#add_promotion').click(function(){
        //     promotion_id=$('#promotion').val();
        //     if (promotion_id==0) {
        //         promotionPrice=$('.promotion_price').val();
        //         $('#promotionPrice').html(promotionPrice);
        //         calculate();
        //         $('#add-discount-modal').modal('toggle');
        //     }else if(promotion_id!=''){
        //         $.ajax({
        //                 type: 'get',
        //                 url: `{{route('admin#promotionCheck')}}`,
        //                 data: { id: promotion_id },
        //                 dataType: 'json',
        //                 success: function(data) {
        //                     if (data.amount != null) {
        //                         promotionPrice = data.amount;
        //                     } else {
        //                         promotionPrice = totalPrice * (data.percentage / 100);
        //                     }
        //                     $('#promotionPrice').html(promotionPrice);
        //                     $('#promotion_id').val(promotion_id);
        //                     $('#promotion_price').val(promotionPrice);

        //                     calculate();
        //                     $('#add-discount-modal').modal('toggle');
        //                 }
        //         });
        //     }
        // })

        function calculate(){
            subTotal=totalPrice+taxPrice-promotionPrice;
            $('#subTotal').html(subTotal);
            $('#payleft').val(subTotal);
            $('#tax_id').val(tax_id);
            $('#tax_price').val(taxPrice);
        }

    </script>
@endsection
