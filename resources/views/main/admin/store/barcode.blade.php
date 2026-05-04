<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Barcodes</title>
    <style>
        *{
            padding: 0;
            margin: 0;
        }
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .barcode {
            text-align: center;
            margin: 10px 0;
            border: 1px solid black;
        }
        @media print {
            @page {
                margin: 0;
            }
        }
    </style>
</head>
<body>
    @for ($i = 0; $i < $count; $i++)
        <div class="barcode">
            <p>{{$storeProduct->product_name}}</p>
            <div>{!! $barcode !!}</div>
            <p>{{$storeProduct->selling_price}} Ks</p>
        </div>
    @endfor
</body>
<script>
    window.onload = function() {
        window.print();
    };
</script>
</html>
