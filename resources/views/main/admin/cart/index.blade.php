@extends('main/admin/layout/master')
@section('title')
    Cart List
@endsection
<?php $section_id="sales" ?>
@section('content')
<div class="row">
    <div class="col-lg-7">
        <div class="title-header">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <button class="btn btn-toggle" type="button">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <h4 class="ms-3">Sales</h4>
                </div>
                <form action="">
                    <div class="search-container">
                        <div class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                        </div>
                        <input type="text" placeholder="Search by item name..." name="search_key" />
                      </div>
                </form>
            </div>
        </div>

        <!-- sale content start  -->
        <div class="sale-category">
            <div class="d-flex align-items-center">
                <a href="javascript:void(0);" class="sale-category-link sale-category-active" onclick="saleClick(event,'productList')">
                    Product List
                </a>
                <a href="javascript:void(0);" class="sale-category-link" onclick="saleClick(event,'scanProduct')">
                    <i class="fa-solid fa-barcode me-2"></i>
                    Scan Barcode
                </a>
            </div>
            <div class="product-list-category">
               <div class="dropdown py-2 dropdown-menu-end">
                    <button class="dropdown-toggle p-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                      @if (empty(request('category')))
                        All
                      @else
                        {{request('category')}}
                      @endif
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{route('admin#cartList')}}">All</a></li>
                        @foreach ($category as $item)
                          <li><a class="dropdown-item" href="{{route('admin#cartList',['category'=>$item->name])}}">{{$item->name}}</a></li>
                        @endforeach
                      </ul>
                </div>
            </div>
        </div>
        <div id="product-list">
            <div class="sale-container mt-3">
                <div class="row mb-5 text-center" id="store_item">
                    @foreach ($product as $item)
                        <div class="pe-0 col-lg-3 col-md-3 col-sm-4 mb-3">
                            <button class="play-sound">
                                <a href="{{route('admin#productAddCart',$item->id)}}" class=" text-decoration-none">
                                    <div id="sale-content">
                                        <img src="{{asset('storage/product/'.$item->product_image)}}" alt="">
                                        <div>
                                            <label for=""><span>{{$item->product_name}}</span></label>
                                            <p>{{$item->selling_price}} {{$item->currency}} <span id="store_item_{{$item->id}}">({{$item->qty}})</span></p>
                                        </div>
                                    </div>
                                </a>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="scan-product" id="scan-product">
            <div class="d-flex align-items-center justify-content-center">
                <div class="d-flex align-items-center justify-content-center">
                    <i class="fa-solid fa-shop me-4"></i>
                    <form action="{{route('admin#addCartBarcode')}}" method="POST">
                        @csrf
                        <label for="" class="mb-2">Enter/Scan Barcode</label>
                        <input type="text" id="barcode" name="id" class="form-control" autofocus autocomplete="off">
                        <span>Press enter after entering barcode to add items.</span>
                    </form>
                </div>
            </div>
        </div>
        <!-- sale content end  -->
    </div>

    <!-- ........ current cart start .............. -->
    <div class="col-lg-5">
        <div class="cart-detail">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <div class="d-flex align-items-center">
                    <h5>Cart Items</h5>
                    <span class="fw-medium ms-3">{{count($cartProduct)}}</span>
                </div>
            </div>
            <div class="">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="item-column">Item</th>
                            <th>Qty</th>
                            <th class="text-end">Price</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cartProduct as $item)
                            <tr class="tr_parents">
                                <td class="pt-2 item-column">{{$item->product_name}}</td>
                                <td>
                                    <div class="quantity">
                                        <button class="minus" aria-label="Decrease"><i class="fa fa-minus"></i></button>
                                        <input type="text" class="input-box" readonly value="{{$item->qty}}">
                                        <button class="plus" aria-label="Increase"><i class="fa fa-plus"></i></button>
                                    </div>
                                </td>
                                <td class="pt-2 text-end item_total">{{$item->price*$item->qty}}</td>
                                <td class="text-end">
                                    <input type="hidden" class="selling_price" value="{{$item->selling_price}}">
                                    <input type="hidden" class="instock" value="{{$item->instock}}">
                                    <input type="hidden" class="id" value="{{$item->id}}">
                                    <input type="hidden" class="instock_type" value="{{$item->instock_type}}">
                                    <a href="{{route('admin#productRemoveCart',$item->id)}}">
                                        <button class="btn text-danger border-0 remove"><i class="fa-regular fa-trash-can"></i></button>
                                    </a>
                                    <a href="" data-bs-toggle="modal" data-bs-target="#edit{{$item->id}}" class="text-decoration-none"><i class="fa-regular fa-pen-to-square"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- if there is nothing in the cart  -->
                <!-- <div class="text-center mx-auto text-secondary my-5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-archive-x"><rect width="20" height="5" x="2" y="3" rx="1"/><path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8"/><path d="m9.5 17 5-5"/><path d="m9.5 12 5 5"/></svg>
                    <h6 class="mt-3">There is nothing in the cart yet.</h6>
                </div> -->
            </div>
        </div>
        <div class="footer-summary">
            <div class="mt-2 total-summary border-top pt-1">
                <div class="d-flex align-items-center justify-content-between px-5">
                    <h6>Subtotal</h6>
                    @if (count($cartProduct)!=0)
                        <input type="hidden" id="currency" value="{{$cartProduct[0]->currency}}">
                    @endif
                    <p><span class="subtotal">0</span></p>
                </div>
            </div>
            <div class="summary-btns mt-1 pb-3 d-flex align-items-center justify-content-between">
                <a href="{{route('admin#productRemoveCartAll')}}" class="text-decoration-none w-100 mx-1">
                    <button class="btn btn-danger w-100">Clear Cart</button>
                </a>
                @if (count($cartProduct)!=0)
                    <a href="{{route('admin#checkOut')}}" class="text-decoration-none w-100 mx-1">
                        <button class="btn w-100">Checkout</button>
                    </a>
                @else
                    <button class="btn btn-success w-100" disabled>Checkout</button>
                @endif
            </div>
        </div>
    </div>

    <!-- ........ current cart end .............. -->
</div>
@foreach ($cartProduct as $item)
    <div class="modal fade" id="edit{{$item->id}}" tabindex="-1" aria-labelledby="edit{{$item->id}}Label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 d-block w-100 text-center" id="edit{{$item->id}}Label">Category Edit</h1>
                <button type="button" class="close-btn border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <form action="{{route('admin#cartUpdateNote')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group mb-3">
                        <label class="mb-1 fw-semibold">Note</label>
                        <textarea name="note" class="form-control" cols="30" rows="10" placeholder="Enter name">{{$item->note}}</textarea>
                    </div>
                    <input type="hidden" name="id" value="{{$item->id}}">
                    <button class="btn w-100 border-0" id="update-btn" >Update</button>
                </form>
            </div>
            </div>
        </div>
    </div>
@endforeach
@endsection
@section('script')
    <script>
        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = false;

        var pusher = new Pusher('6f1ebfd4aa55bf62ed9a', {
          cluster: 'ap1'
        });

        var channel = pusher.subscribe('stock-change-channel');
        channel.bind('stock-change-event', function(data) {
            document.getElementById(`store_item_${data.store_item_id}`).innerHTML=`(${data.store_item_qty})`;
        });
    </script>
    <script>
        let qtyLock=false;

        $('.plus').click(function(){
            $parents=$(this).parents('tr');
            itemTotal($parents,'plus');
        })

        $('.minus').click(function(){
            $parents=$(this).parents('tr');
            $count=$parents.find('.input-box').val()*1;
            if ($count>1) {
                itemTotal($parents,'minus');
            }
        })

        function itemTotal($parents,$status){
            $id=$parents.find('.id').val();
            // if (qtyLock) {
            //     return;
            // }
            qtyLock=true;
                $.ajax({
                    type:'post',
                    url:`{{route('admin#changeQty')}}`,
                    data:{
                        _token:"{{ csrf_token() }}",
                        id:$id,
                        status:$status,
                    },
                    dataType:'json',
                    success:function(data){
                        $parents.find('.input-box').val(data.qty);
                        $parents.find('.item_total').html(data.price);
                        subTotal();
                    },
                    complete:function(){
                        qtyLock=false;
                    }
                })
        }

        function subTotal(){
            let subTotal = 0;  // Initialize subtotal
            let trElements = document.querySelectorAll('.tr_parents');  // Select all table rows
            trElements.forEach(element => {
                let itemTotalElement = element.querySelector('.item_total'); // Find the item total element inside the row
                if (itemTotalElement) {
                    let itemTotal = parseFloat(itemTotalElement.innerHTML) || 0; // Convert to a number, default to 0 if invalid
                    subTotal += itemTotal; // Add to subtotal
                }
            });
            $currency=$('#currency').val() ?? "MMK";
            $('.subtotal').html(subTotal+' '+$currency);
        }

        function focusBarcodeInput() {
            let barcodeInput = $("#barcode");
            barcodeInput.val("").trigger("focus"); // Clear input & force focus
        }


        $(document).on("click", function(e) {
            if (!$(e.target).is("input, textarea, select, button")) {
                focusBarcodeInput();
            }
        });

        setTimeout(focusBarcodeInput, 500);
        subTotal();
    </script>
@endsection
