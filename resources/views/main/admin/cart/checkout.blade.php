@extends('main/admin/layout/master')
@section('title')
    Check Out
@endsection
<?php $section_id="payment" ?>
@section('content')
<div class="row">
    <div class="col-lg-7">
        <div class="title-header">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <button class="btn btn-toggle" type="button">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <h4 class="ms-3">Check Out</h4>
                </div>
            </div>
        </div>

        <!-- payment start  -->
        <div class="payment-content">
            <div class="row d-none mb-3" id="userInfo">
                <div class="col-md-6">
                    <div class="rounded shadow-sm px-3 py-2">
                        <div class="py-2">
                            <h6>Name - <span id="userName"></span></h6>
                        </div>
                        <div class="py-2">
                            <h6>Email - <span id="userEmail"></span></h6>
                        </div>
                        <div class="py-2">
                            <h6>Phone - <span id="userPhone"></span></h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="payment-de-container mt-1">
                <div class="row">
                    <div class="col-lg-3 col-sm-6 mb-2">
                        <div>
                            <p>Total Payment</p>
                            <label for="" class="t-payment"><span class="totalPayment">0</span></label>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 mb-2">
                        <div>
                            <p>Total Paying</p>
                            <label for="" class="t-paying"><span class="totalPaying">0</span></label>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 mb-2">
                        <div>
                            <p>Payment Left</p>
                            <label for="" class="p-left"><span class="payLeft">0</span></label>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 mb-2">
                        <div>
                            <p>Change</p>
                            <label for="" class="p-change"><span class="payChange">0</span></label>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="payment-form">
                        <form action="">
                            <div id="payment-rows">
                                <div class="row form-group w-100 pay_input_parents">
                                    <div class="col-lg-6 col-sm-6 mb-3">
                                        <label for="" class="mb-1">Amount</label>
                                        <input type="text" min="0" class="form-control calc-display pay_input">
                                    </div>
                                    <div class="col-lg-6 col-sm-6 mb-3">
                                        <label for="" class="mb-1">Method</label>
                                        <div class="d-flex align-items-center">
                                            <select class="form-control payment_input">
                                                @foreach ($payment as $item)
                                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                            <i class="fa-solid fa-chevron-down" id="method-drop-icon"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button class="btn w-100 add-btn" id="add-payment-method" type="button">
                                <i class="fa-solid fa-plus me-2"></i>
                                <label for="">Add Another Payment Method</label>
                            </button>

                            <div class="calculator mt-4">
                                <div class="row">
                                    <div class="col-lg-9 pe-0">
                                        <div class="calculator-buttons">
                                            <button class="calc-btn" data-value="1" type="button">1</button>
                                            <button class="calc-btn" data-value="2" type="button">2</button>
                                            <button class="calc-btn" data-value="3" type="button">3</button>
                                            <button class="calc-btn" data-value="4" type="button">4</button>
                                            <button class="calc-btn" data-value="5" type="button">5</button>
                                            <button class="calc-btn" data-value="6" type="button">6</button>
                                            <button class="calc-btn" data-value="7" type="button">7</button>
                                            <button class="calc-btn" data-value="8" type="button">8</button>
                                            <button class="calc-btn" data-value="9" type="button">9</button>
                                            <button class="calc-btn" data-value="0" type="button">0</button>
                                            <button class="calc-btn" data-value="." type="button"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-dot"><circle cx="12.1" cy="12.1" r="1"/></svg></button>
                                            <button class="calc-btn" data-value="00" type="button">00</button>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 ps-0">
                                        <div class="function-buttons">
                                            <button class="calc-btn clear-btn" type="button">Clear</button>
                                            <button class="calc-btn backspace-btn" type="button"><i class="fa-solid fa-arrow-left-long"></i></button>
                                            <button class="calc-btn pay-btn" type="button">Pay</button>
                                            <a href="{{route('admin#cartList')}}" class="cancel-btn">
                                                <span>Cancel</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- payment end  -->
    </div>

    <!-- ........ current cart start .............. -->
    <div class="col-lg-5">
        <div class="cart-detail">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <div class="d-flex align-items-center">
                    <h5>Cart Items</h5>
                    <span class="fw-medium ms-3">{{count($cartProduct)}}</span>
                </div>
                <div class="title-right">
                    <div class="d-flex align-items-center px-2">
                        <i class="fa-regular fa-user me-2"></i>
                        <label for="">{{Auth::user()->name}}</label>
                    </div>
                </div>
            </div>
            <div class="">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Qty</th>
                            <th class="text-end">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cartProduct as $item)
                            <tr class="tr_parents">
                                <td class="pt-2">{{$item->product_name}}</td>
                                <td class="text-center">{{$item->qty}}</td>
                                <td class="pt-2 text-end item_total">{{$item->price*$item->qty}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if (count($cartProduct)!=0)
                    <input type="hidden" id="currency" value="{{$cartProduct[0]->currency}}">
                @endif
            </div>
        </div>
        <div id="payment-footer">
            <div class="add-modal">
                <label for="" class="mx-auto fw-medium">Payment Summary</label>
                <div class="d-flex align-items-center justify-content-between">
                    <button type="button" data-bs-toggle="modal" data-bs-target="#add-customer" class="me-4 customer_button">Customer</button>
                    <button type="button" data-bs-toggle="modal" data-bs-target="#add-tax" class="me-4 tax_button">Tax</button>
                    <button type="button" data-bs-toggle="modal" data-bs-target="#add-discount" class="me-4 promotion_button">Discount</button>
                    <button type="button" data-bs-toggle="modal" data-bs-target="#add-table" class="me-4">Table</button>
                </div>
            </div>

            <div class="mt-3 total-summary">
                <div class="d-flex align-items-center justify-content-between px-5">
                    <h6>Table</h6>
                    <p><span id="tableName"></span></p>
                </div>
                <div class="d-flex align-items-center justify-content-between px-5">
                    <h6>Subtotal</h6>
                    <p><span id="subTotal">0</span></p>
                </div>
                <div class="d-flex align-items-center justify-content-between px-5">
                    <h6>Taxes</h6>
                    <p><span id="taxPrice">0</span></p>
                </div>
                <div class="d-flex align-items-center justify-content-between px-5">
                    <h6>Discount</h6>
                    <p><span id="promotionPrice">0</span></p>
                </div>
                <div class="d-flex align-items-center justify-content-between px-5">
                    <h6>Total Payment</h6>
                    <p class="text-success"><span class="totalPayment">0</span></p>
                </div>
            </div>
            <div>
                <form action="{{route('admin#order')}}" method="post" id="order">
                    @csrf
                    <input type="hidden" name="cartProduct" value="{{json_encode($cartProduct)}}">
                    <input type="hidden" name="paymentMethod" id="paymentMethod_input">
                    <input type="hidden" name="promotionId" id="promotionId_input">
                    <input type="hidden" name="promotionPrice" id="promotionPrice_input">
                    <input type="hidden" name="taxId" id="taxId_input">
                    <input type="hidden" name="taxPrice" id="taxPrice_input">
                    <input type="hidden" name="shop_id" value="{{$shop->id}}">
                    <input type="hidden" name="shop_name" value="{{$shop->name}}">
                    <input type="hidden" name="payAmount" id="payAmount_input">
                    <input type="hidden" name="subTotal" id="subTotal_input">
                    <input type="hidden" name="userName" id="userName_input">
                    <input type="hidden" name="userEmail" id="userEmail_input">
                    <input type="hidden" name="userPhone" id="userPhone_input">
                    <input type="hidden" name="currency" id="currency_input">
                    <input type="hidden" name="table" id="table_input">
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="add-customer" data-bs-keyboard="false" tabindex="-1" aria-labelledby="add-customerLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                 <div class="modal-header">
                    <h1 class="modal-title fs-5 d-block w-100 text-center" id="add-customerLabel">Add Customer</h1>
                    <button type="button" class="close-btn border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <div class="modal-body">
                    <div class="mt-1">
                        <div class="form-group mb-3">
                            <label class="mb-1">Name</label>
                            <input type="text" name="user_name" id="user_name" class="form-control">
                        </div>
                        <div class="form-group mb-3">
                            <label class="mb-1">Email</label>
                            <input type="text" name="user_email" id="user_email" class="form-control">
                        </div>
                        <div class="form-group mb-3">
                            <label class="mb-1">Phone</label>
                            <input type="text" name="user_phone" id="user_phone" class="form-control">
                        </div>
                        <button type="button" class="btn add-btn w-100 my-2" id="add_customer">Add</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="add-discount" data-bs-keyboard="false" tabindex="-1" aria-labelledby="add-discountLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5 d-block w-100 text-center" id="add-discountLabel">Add Discount</h1>
                    <button type="button" class="close-btn border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <div class="modal-body">
                    <div class="mt-1">
                        <div class="form-group mb-3">
                            <label class="mb-1">Name</label>
                            <div class="d-flex align-items-center">
                                <select id="promotion" class="form-control">
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
                            <label class="mb-1">Price</label>
                            <input type="number" class="form-control promotion_price">
                        </div>

                        <button type="button" class="btn add-btn w-100 my-2" id="add_promotion">Add</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="add-tax" data-bs-keyboard="false" tabindex="-1" aria-labelledby="add-taxLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5 d-block w-100 text-center" id="add-taxLabel">Add Tax</h1>
                    <button type="button" class="close-btn border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <div class="modal-body">
                    <div class="mt-1">
                        <div class="form-group mb-3">
                            <label class="mb-1">Name</label>
                            <div class="d-flex align-items-center">
                                <select id="tax" class="form-control">
                                    <option value="">Choose tax</option>
                                    @foreach ($tax as $item)
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                                <i class="fa-solid fa-chevron-down" id="method-drop-icon2"></i>
                            </div>
                        </div>
                        <button type="button" class="btn add-btn w-100 my-2" id="add_tax">Add</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="add-table" data-bs-keyboard="false" tabindex="-1" aria-labelledby="add-tableLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5 d-block w-100 text-center" id="add-tableLabel">Add Tax</h1>
                    <button type="button" class="close-btn border-none shadow-none" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <div class="modal-body">
                    <div class="mt-1">
                        <div class="form-group mb-3">
                            <label class="mb-1">Table</label>
                            <div class="d-flex align-items-center">
                                <select id="table" class="form-control">
                                    <option value="0" data-name='Take Away'>Take Away</option>
                                    @foreach ($table as $item)
                                        <option value="{{$item->id}}" data-name='{{$item->name}}'>{{$item->name}}</option>
                                    @endforeach
                                </select>
                                <i class="fa-solid fa-chevron-down" id="method-drop-icon2"></i>
                            </div>
                        </div>
                        <button type="button" class="btn add-btn w-100 my-2" id="add_table">Add</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ........ current cart end .............. -->
</div>
@endsection
@section('script')
<script>
    document.addEventListener("DOMContentLoaded", () => {
        let display = null;
        let subTotal = 0;  // Initialize subtotal
        let promotionPrice=0;
        let taxPrice=0;
        let totalPayment=0;
        let promotion_id;
        let tax_id;
        let payTotal=0;
        $currency=$('#currency').val();

        function updateFocusedInput() {
            document.querySelectorAll(".calc-display").forEach((input) => {
            input.addEventListener("focus", () => {
                display = input;
            });
            });
        }

        updateFocusedInput();

        const firstInput = document.querySelector(".calc-display");
        if (firstInput) {
            firstInput.focus();
            display = firstInput;
        }

        document.querySelectorAll(".calc-btn").forEach((btn) => {
            btn.addEventListener("click", (event) => {
            event.preventDefault();
            if (!display) return;
            const value = btn.getAttribute("data-value");

            if (value === ".") {
                const currentValue = display.value;
                const lastNumber = currentValue.split(/[^0-9.]+/).pop();
                if (lastNumber.includes(".")) {
                return;
                }
            }

            if (value) {
                display.value += value;
            }
            });
        });

        document.querySelector(".clear-btn").addEventListener("click", () => {
            if (display) {
            display.value = "";
            }
        });

        document.querySelector(".backspace-btn").addEventListener("click", () => {
            if (display) {
            display.value = display.value.slice(0, -1);
            }
        });

        // Adding new payment rows
        const paymentContainer = document.getElementById("payment-rows");
        document.getElementById("add-payment-method").addEventListener("click", (e) => {
            e.preventDefault();
            let payment=@json($payment);
            const newRow = document.createElement("div");
            newRow.classList.add("d-flex", "align-items-center", "justify-content-between", "payment-row");
            let paymentMethod;
            payment.forEach((element)=>{
                paymentMethod+=`<option value="${element.id}">${element.name}</option>`;
            })

            newRow.innerHTML = `
            <div class="row form-group w-100 pay_input_parents">
                <div class="col-lg-6 col-sm-6 mb-3">
                <label for="" class="mb-1">Amount</label>
                <input type="text" class="form-control calc-display pay_input">
                </div>
                <div class="col-lg-6 col-sm-6 mb-3">
                <label for="" class="mb-1">Method</label>
                <div class="d-flex align-items-center">
                    <select name="" id="" class="form-control payment_input">
                        ${paymentMethod}
                    </select>
                    <i class="fa-solid fa-chevron-down" id="method-drop-icon"></i>
                </div>
                </div>
            </div>
            <div id="form-remove">
                <button type="button" class="btn remove-btn"><i class="fa-solid fa-minus"></i></button>
            </div>
            `;

            paymentContainer.appendChild(newRow);
            updateFocusedInput();

            const removeBtn = newRow.querySelector(".remove-btn");
            removeBtn.addEventListener("click", () => {
                newRow.remove();
                let payParents=document.querySelectorAll('.pay_input_parents');
                payTotal=0;
                payParents.forEach((element)=>{
                    payTotal+=parseFloat(element.querySelector('.pay_input').value);
                });
                let payLeft=totalPayment-payTotal;
                let payChange=payTotal-totalPayment;
                $('.totalPaying').html(payTotal);
                $('.payLeft').html(payLeft>0?payLeft:0);
                $('.payChange').html(payChange>0?payChange:0);
            });

            newRow.querySelector(".calc-display").focus();
        });

        function total(){
            subTotal=0
            let trElements = document.querySelectorAll('.tr_parents');  // Select all table rows
            trElements.forEach(element => {
                let itemTotalElement = element.querySelector('.item_total'); // Find the item total element inside the row
                if (itemTotalElement) {
                    let itemTotal = parseFloat(itemTotalElement.innerHTML) || 0; // Convert to a number, default to 0 if invalid
                    subTotal += itemTotal; // Add to subtotal
                }
            });
            let taxPrice = parseFloat(document.querySelector('#taxPrice').innerHTML) || 0;
            let promotionPrice = parseFloat(document.querySelector('#promotionPrice').innerHTML) || 0;
            totalPayment= subTotal+taxPrice-promotionPrice<0?0:subTotal+taxPrice-promotionPrice;
            $('#subTotal').html(subTotal+' '+$currency);
            $('.totalPayment').html(totalPayment+' '+$currency);
        }

        $('#add_tax').click(function(){
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
                                taxPrice = subTotal * (data.percentage / 100);
                            }
                            $('#taxPrice').html(taxPrice+' '+$currency);
                            total();
                            calculate();
                            $('#add-tax').modal('toggle');
                            $('.tax_button').hide();
                        }
                    });
            }
        })

        $('#promotion').change(function(){
            if ($(this).val()==0) {
                $('.promotion_price_container').removeClass('d-none');
            }else{
                $('.promotion_price_container').addClass('d-none');
            }
        })

        $('#add_promotion').click(function(){
            promotion_id=$('#promotion').val();
            if (promotion_id===0) {
                promotionPrice=$('.promotion_price').val();
                $('#promotionPrice').html(promotionPrice+' '+$currency);
                total();
                calculate();
                $('#add-discount').modal('toggle');
                $('.promotion_button').hide();
            }else if(promotion_id!=''){
                $.ajax({
                        type: 'get',
                        url: `{{route('admin#promotionCheck')}}`,
                        data: { id: promotion_id },
                        dataType: 'json',
                        success: function(data) {
                            if (data.amount != null) {
                                promotionPrice = data.amount;
                            } else {
                                promotionPrice = subTotal * (data.percentage / 100);
                            }
                            $('#promotionPrice').html(promotionPrice+' '+$currency);
                            total();
                            calculate();
                            $('#add-discount').modal('toggle');
                            $('.promotion_button').hide();
                        }
                });
            }
        })

        $('#add_customer').click(function () {
            $user_name=$('#user_name').val();
            $user_email=$('#user_email').val();
            $user_phone=$('#user_phone').val();
            if ($user_name!=''&&$user_phone!='') {
                $('#userName_input').val($user_name);
                $('#userEmail_input').val($user_email);
                $('#userPhone_input').val($user_phone);
                $('#userName').html($user_name);
                $('#userEmail').html($user_email);
                $('#userPhone').html($user_phone);
                $('#userInfo').removeClass('d-none');
                $('#add-customer').modal('toggle');
                $('.customer_button').hide();
            }
        })

        $('#add_table').click(function(){
            $table=$('#table').val();
            let selected = $('#table option:selected')
            let name = selected.data('name');
            $('#table_input').val(name);
            $('#tableName').html(name);
            $('#add-table').modal('toggle');
        })

        $(document).on("change",".pay_input",function(){
            calculate();
        });

        $('.calc-btn').click(function(){
            calculate();
        })


        document.querySelector(".pay-btn").addEventListener("click", () => {
            let payParents=document.querySelectorAll('.pay_input_parents');
            let paymentMethod=[];
            payParents.forEach((element)=>{
                $pay_amount=element.querySelector('.pay_input').value;
                $pay_method=element.querySelector('.payment_input').value;
                paymentMethod.push({
                    'amount':$pay_amount,
                    'method':$pay_method,
                });
            });
            $('#paymentMethod_input').val(JSON.stringify(paymentMethod));
            $('#promotionId_input').val(promotion_id);
            $('#promotionPrice_input').val(promotionPrice);
            $('#taxId_input').val(tax_id);
            $('#taxPrice_input').val(taxPrice);
            $('#subTotal_input').val(totalPayment);
            $('#currency_input').val($currency);
            if (payTotal>=0) {
                $('#payAmount_input').val(payTotal);
            }
            if (promotionPrice>totalPayment) {
                $('#payAmount_input').val(0);
            }
            $('#order').submit();
        });

        function calculate(){
            let payParents=document.querySelectorAll('.pay_input_parents');
            payTotal=0;
            payParents.forEach((element)=>{
                let temp=element.querySelector('.pay_input').value;
                payTotal+=parseFloat(temp==''?0:temp);
            });
            let payLeft=totalPayment-payTotal;
            let payChange=payTotal-totalPayment;
            let totalPayLeft=payLeft>0?payLeft:0;
            let totalPayChange=payChange>0?payChange:0;
            $('.totalPaying').html(payTotal+' '+$currency);
            $('.payLeft').html(totalPayLeft+' '+$currency);
            $('.payChange').html(totalPayChange+' '+$currency);
        }

        total();


    });
</script>
<script>
</script>

@endsection
