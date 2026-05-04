<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <style>
        body {
            /* height: 99vh; */
            font-family: Arial, sans-serif;
            background-image: linear-gradient(to right top, #fedd30, #fddc34, #fcda39, #fad93c, #f9d840, #f9d843, #f9d745, #f9d748, #fad84a, #fcd84d, #fdd94f, #fed951);
        }
        .date{
            display: flex;
            justify-content: right;
            font-size: 13pt;
        }
        .date span{
            padding-right: 30px;
        }
        .receipt-container{
            padding: 50px 20px;
            border: none;
            box-shadow: none;
            background-color: transparent;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .receipt-header{
            margin-top: 10px;
            display: flex;
            justify-content: space-between;
        }
        .receipt-header > div:first-child{
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .receipt-header > div:first-child > div{
            margin-left: 20px;
        }
        .receipt-header > div:first-child  img{
            display: block;
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
        }
        .receipt-header > div:first-child  h1{
            text-transform: uppercase;
            font-size: 23pt;
            color: rgb(15, 79, 208);
        }
        .receipt-header > div:last-child h2{
            margin-right: 20px;
            margin-top: 40px;
            font-weight: 500;
        }
        .receipt-table{
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            flex-grow: 1;
        }
        .receipt-table thead,
        .receipt-footer > span{
            background-color: rgb(0, 85, 255);
            border: 1px solid rgb(0, 85, 255);
            color: white;
        }
        .receipt-table th{
            padding: 8px;
        }
        .receipt-table td{
            border: 1px solid rgb(0, 85, 255);
            padding: 2px 5px;
            font-size: 11pt;
        }
        .receipt-table tbody{
            background-color: rgb(248, 232, 150);
        }
        .receipt-table tbody tr:last-child td{
            padding: 10px;
        }
        .receipt-table .text-center{
            text-align: center;
        }
        .receipt-table .text-right{
            text-align: right;
        }
        .receipt-table tbody tr td:nth-child(3){
            max-width: 300px;
            line-height: 25px;
        }
        .receipt-footer{
            text-align: right;
            font-weight: bold;
            margin-top: 20px;
            padding-top: 10px;
        }
        .receipt-footer > span{
            padding: 10px 20px;
        }
        .receipt-letter{
            margin-top: 18px;
            text-transform: uppercase;
        }
        .receipt-table td{
            text-align: end;
        }
        .justify-content-end{
            display: flex;
            justify-content: end;
        }
        @media print {
            body {
                background-image: linear-gradient(to right top, #fedd30, #fddc34, #fcda39, #fad93c, #f9d840, #f9d843, #f9d745, #f9d748, #fad84a, #fcd84d, #fdd94f, #fed951) !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div>
            <div class="date">
                <span>Date : 3/4/2025</span>
            </div>

            <div class="receipt-header">
                <div>
                    <img src="{{asset('storage/website/'.$websiteInfo->logo)}}" alt="">
                    <div>
                        <h1>{{$websiteInfo->name}}</h1>
                        <p>{!!$websiteInfo->contact!!}</p>
                    </div>
                </div>
                <div>
                    <h2>Sales Receipt</h2>
                </div>
            </div>

            <table class="receipt-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Description</th>
                        <th>Qty</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orderItem as $item)
                        <tr>
                            <td class="text-center">{{$loop->index+1}}</td>
                            <td>{{$item->product_name}}</td>
                            <td class="text-center">{{$item->qty}}</td>
                            <td class="text-right">{{number_format($item->price)}} {{$item->currency}}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="2">Subtotal</td>
                        <td colspan="2">{{number_format($order->total_price)}} {{$order->currency}}</td>
                    </tr>
                    @if ($order->promotion_id!=null)
                        <tr>
                            <td colspan="2">Promotion</td>
                            <td colspan="2">- {{number_format($order->promotion_price)}} {{$order->currency}}</td>
                        </tr>
                    @endif
                    @if ($order->tax_id!=null)
                        <tr>
                            <td colspan="2">Tax</td>
                            <td colspan="2">+ {{number_format($order->tax_price)}} {{$order->currency}}</td>
                        </tr>
                    @endif
                    <tr>
                        <td colspan="2">Total Price</td>
                        <td colspan="2">{{number_format($order->subtotal)}} {{$order->currency}}</td>
                    </tr>
                </tbody>
            </table>
            <div class="justify-content-end">
                <table>
                    <tr>
                        <td colspan="4">Payment</td>
                    </tr>
                    @foreach ($paymentMethod as $item)
                        <tr>
                            <td colspan="2">{{$item['method']}}</td>
                            <td colspan="2">{{number_format($item['amount'])}} {{$order->currency}}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="2">Pay Amount</td>
                        <td colspan="2">{{number_format($order->pay_amount)}} {{$order->currency}}</td>
                    </tr>
                    @if ($order->change>0)
                        <tr>
                            <td colspan="2">Change</td>
                            <td colspan="2">{{number_format($order->change)}} {{$order->currency}}</td>
                        </tr>
                    @endif
                    @if ($order->pay_left>0)
                        <tr>
                            <td colspan="2">Pay Left</td>
                            <td colspan="2">{{number_format($order->pay_left)}} {{$order->currency}}</td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>

        <div class="receipt-letter">
            <p>Thank You !!</p>
            <p>{{$websiteInfo->name}} | we guarntee authentic 18k gold and gemstones.</p>
        </div>
    </div>
</body>
<script>
    // Automatically trigger print dialog when the page is fully loaded
    window.onload = function() {
        window.print();
    };
</script>
</html>
