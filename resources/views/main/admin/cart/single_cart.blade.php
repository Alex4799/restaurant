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
                        <li><a class="dropdown-item">All</a></li>
                        @foreach ($category as $item)
                          <li><a class="dropdown-item">{{$item->name}}</a></li>
                        @endforeach
                      </ul>
                </div>
            </div>
        </div>
        <div id="alert"></div>
        <div id="product-list">
            <div class="sale-container mt-3">
                <div class="row mb-5 text-center" id="store_item_container">
                    @foreach ($product as $item)
                        <div class="pe-0 col-lg-3 col-md-3 col-sm-4 mb-3 store_item_parents">
                            <button class="play-sound">
                                <a class=" text-decoration-none store_item">
                                    <div id="sale-content">
                                        <img src="{{asset('storage/product/'.$item->product_image)}}" alt="">
                                        <div>
                                            <label for=""><span>{{$item->product_name}}</span></label>
                                            <p>{{$item->selling_price}} {{$item->currency}} <span id="store_item_{{$item->id}}">({{$item->qty}})</span></p>
                                            <input type="hidden" value="{{$item->id}}" class="store_item_id">
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
                    <span class="fw-medium ms-3 cart_count">0</span>
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
                    <tbody class="cart_container">
                    </tbody>
                </table>
                <div class="text-center mx-auto text-secondary my-5" id="nothing_cart">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-archive-x"><rect width="20" height="5" x="2" y="3" rx="1"/><path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8"/><path d="m9.5 17 5-5"/><path d="m9.5 12 5 5"/></svg>
                    <h6 class="mt-3">There is nothing in the cart yet.</h6>
                </div>
            </div>
        </div>
        <div class="footer-summary">
            <div class="mt-2 total-summary border-top pt-1">
                <div class="d-flex align-items-center justify-content-between px-5">
                    <h6>Subtotal</h6>
                    <p><span class="subtotal">0</span></p>
                </div>
            </div>
            <div class="summary-btns mt-1 pb-3 d-flex align-items-center justify-content-between">
            <a class="text-decoration-none w-100 mx-1 remove_all_cart">
                <button class="btn btn-danger w-100">Clear Cart</button>
            </a>
            <button class="btn btn-success w-100 checkout">Checkout</button>
            </div>
            <form action="{{route('admin#createCart')}}" method="post" id='checkout_from'>
                @csrf
                <input type="hidden" name="cart" class="cart_data">
            </form>
        </div>
    </div>

    <!-- ........ current cart end .............. -->
</div>

<div class="modal_container"></div>
@endsection

@section('script')
    <script>
        let cart=[];
        let storeItem=[];
        let barcodeItem=[];

        let product=@json($product);

        product.forEach(element => {
            storeItem[element.id]=element;
            if (element.barcode) {
                barcodeItem[element.barcode]=element;
            }
        });

        $(document).on("click",".store_item",function(){
            $parents=$(this).parents('.store_item_parents');
            $id=$parents.find('.store_item_id').val();
            let selectItem=storeItem[$id];
            if (cart.length!=0) {
                let check=cart.find(element=>element.store_item_id==$id);
                if (check) {
                    showAlert('warning',"This item is already selected.");
                    return;
                }

                if(cart[0].currency!=selectItem.currency){
                    showAlert('warning',"Can't choose different currency.");
                    return;
                }

                if(cart[0].store_id!=selectItem.store_id){
                    showAlert('warning',"Can't choose different store.");
                    return;
                }
            }
            if (selectItem.qty>0) {
                cart.push({
                    'user_id':{{Auth::user()->id}},
                    'store_item_id':selectItem.id,
                    'store_id':selectItem.store_id,
                    'product_name':selectItem.product_name,
                    'qty':1,
                    'price':selectItem.selling_price,
                    'instock':selectItem.stock,
                    'currency':selectItem.currency,
                    'note':'',
                });
                renderCart();
                calculateSubtotal();
            }else{
                let message='This item is not enought instock.';
                showAlert('warning',message);
            }

        });

        $(document).on("click",".plus",function(){
            $parents=$(this).parents('.cart_parents');
            let index=$parents.find('.cart_index').val();
            let cartItem=cart[index];
            let item=storeItem[cartItem.store_item_id];
            if (item.qty>cartItem.qty || item.instock_type) {
                cart[index].qty+=1;
                $parents.find('.input-box').val(cart[index].qty);
                $parents.find('.item_total').html(cart[index].price*cart[index].qty);
                calculateSubtotal();
            }
        });

        $(document).on("click",".minus",function(){
            $parents=$(this).parents('.cart_parents');
            let index=$parents.find('.cart_index').val();
            let cartItem=cart[index];
            if (cartItem.qty>1) {
                cart[index].qty-=1;
                $parents.find('.input-box').val(cart[index].qty);
                $parents.find('.item_total').html(cart[index].price*cart[index].qty);
                calculateSubtotal()
            }
        });

        $(document).on("click",".remove",function(){
            $parents=$(this).parents('.cart_parents');
            let index=$parents.find('.cart_index').val();
            let cartItem=cart[index];
            cart.splice(index,1);
            renderCart();
            calculateSubtotal();
        });

        $(document).on("click",".update_note_btn",function(){
            $parents=$(this).parents('.modal_parents');
            let index=$parents.find('.model_index').val();
            cart[index].note=$(`.modal_note_${index}`).val();
            $(`#edit${index}`).modal('hide');
            renderCart();
        });

        $('.remove_all_cart').click(function(){
            cart=[];
            renderCart();
            calculateSubtotal();
        });

        $('.checkout').click(function(){
            if (cart.length!=0) {
                let json_cart=JSON.stringify(cart);
                $('.cart_data').val(json_cart);
                $('#checkout_from').submit();
            }else{
                showAlert('warning','Need to select item first.');
            }
        })

        function renderCart(){
            let temp='';
            let modal='';
            let count=0;
            if (cart.length!=0) {
                cart.forEach((element,index)=>{
                    temp+=`
                        <tr class="cart_parents">
                            <td class="pt-2 item-column">${element.product_name}</td>
                            <td>
                                <div class="quantity">
                                    <button class="minus" aria-label="Decrease"><i class="fa fa-minus"></i></button>
                                    <input type="text" class="input-box" readonly value="${element.qty}">
                                    <button class="plus" aria-label="Increase"><i class="fa fa-plus"></i></button>
                                </div>
                            </td>
                            <td class="pt-2 text-end item_total">${element.price*element.qty}</td>
                            <td class="text-end">
                                <input type="hidden" value="${index}" class="cart_index">
                                <button class="btn text-danger border-0 remove"><i class="fa-regular fa-trash-can"></i></button>
                                <a href="" data-bs-toggle="modal" data-bs-target="#edit${index}" class="text-decoration-none"><i class="fa-regular fa-pen-to-square"></i></a>
                            </td>
                        </tr>
                    `;
                    count+=1;
                    modal+=`
                        <div class="modal fade" id="edit${index}" tabindex="-1" aria-labelledby="edit${index}Label" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5 d-block w-100 text-center" id="edit${index}Label">Category Edit</h1>
                                    <button type="button" class="close-btn border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
                                </div>
                                <div class="modal-body">
                                    <div class='modal_parents'>
                                        <div class="form-group mb-3">
                                            <label class="mb-1 fw-semibold">Note</label>
                                            <textarea name="note" class="form-control modal_note_${index}" cols="30" rows="10" placeholder="Enter name">${element.note}</textarea>
                                        </div>
                                        <input type="hidden" class="model_index" value="${index}">
                                        <button class="btn w-100 border-0 update_note_btn" id="update-btn" >Update</button>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
                $('.cart_container').html(temp);
                $('.modal_container').html(modal);
                $('#nothing_cart').hide();
            }else{
                $('.cart_container').html('');
                $('.modal_container').html('');
                $('#nothing_cart').show();
            }
            $('.cart_count').html(count);
        }

        function calculateSubtotal(){
            let totalPrice=0;
            let currency;
            cart.forEach(element=>{
                totalPrice+=element.price*element.qty;
                currency=element.currency;
            })
            $('.subtotal').html(`${totalPrice} ${currency}`);
        }

        function showAlert($status,$message){
            $('#alert').html(`
                <div class="alert alert-${$status} alert-dismissible fade show" role="alert">
                    ${$message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `)
        }

    </script>
@endsection
