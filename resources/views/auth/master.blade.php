<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nanobot - POS</title>

    <link rel="shortcut icon" href="{{asset('admin/image/06cgIF4Qj26l7tdRyV2e6Fo-7.webp')}}" id="favicon" type="image/x-icon">

    <!-- bootstrap css  -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css">

    <!-- font awesome css  -->
    <link rel="stylesheet" href="{{asset('admin/font-awesome/css/all.min.css')}}">

    <style>
        #login-content{
            height: 100vh;
        }
        #login-content .input-div{
            position: relative;
            display: grid;
            grid-template-columns: 7% 93%;
            margin: 18px 0;
            padding: 5px 0 5px 8px;
            border: 1px solid rgb(235, 233, 233);
            border-radius: 8px;
        }
        #login-content .input-div:has(input:focus) {
            border: 1px solid #55a963;
        }
        #login-content .row > div:last-child > div{
            border: 1px solid rgb(235, 233, 233);
            padding: 30px;
            border-radius: 8px;
        }
        #login-content .row > div:last-child{
            padding: 0 80px 0 0;
        }
        #login-content .row > div:last-child p{
            display: block;
            font-size: 10pt;
        }
        .input-i{
            display: flex;
            justify-content: center;
            align-items: center;
            color: #55a963;
        }
        .input-div > div{
            position: relative;
            height: 43px;
        }
        .input-div > div > input{
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            border: none;
            outline: none;
            background: none;
            padding: 0.5rem 0.7rem;
            font-size: 1.1rem;
            font-family: 'poppins', sans-serif;
        }
        .input-div > div > input::placeholder{
            font-size: 12pt;
        }
        #login-content .btn{
            display: block;
            width: 100%;
            height: 46px;
            border-radius: 10px;
            outline: none;
            border: none;
            background-color: #55a963;
            font-size: 1.1rem;
            font-family: 'Poppins', sans-serif;
            margin-top: 20px;
            cursor: pointer;
            color: white;
        }
        form a{
            color: rgb(17, 46, 127);
            text-decoration: none;
            font-size: 11pt;
        }
        #web-logo{
            display: block;
            margin: auto;
            width: 200px;
            height: 80px;
            object-fit: cover;
            margin-bottom: 20px;
        }
        @media screen and (max-width: 991px){
            #login-content .row > div:first-child{
                display: none;
            }
            #login-content .row > div:last-child{
                padding: 0;
                width: 350px;
            }
            #login-content .row > div:last-child > div{
                padding: 30px 20px;
            }
        }
        .btn-danger{
            display: block;
            width: 100%;
            height: 46px;
            border-radius: 10px;
            outline: none;
            border: none;
            background-color: #92121d;
            font-size: 1.1rem;
            font-family: 'Poppins', sans-serif;
            margin-top: 20px;
            cursor: pointer;
            color: white;
        }
        .text-danger{
            color: red;
        }
        input:-webkit-autofill {
            -webkit-box-shadow: 0 0 0px 1000px transparent inset !important;
            -webkit-text-fill-color: #000000 !important;
            background-color: transparent !important;
            transition: background-color 5000s ease-in-out 0s;
        }
    </style>

</head>
<body>

    <!-- login form start  -->
    <div class="container-fluid" id="login-content">
        <div class="row align-items-center justify-content-center h-100 px-3">
            <div class="col-lg-6">
                <div id="web-logo">
                    <img src="{{asset('admin/image/transparent.png')}}" id="logo-img" class="w-100 h-100 object-fit-cover d-block" alt="">
                </div>
                <img src="{{asset('admin/image/1732340422217.png')}}" class="w-100 d-block" alt="">
            </div>
            @yield('content')
        </div>
    </div>
    <!-- login form end  -->

</body>
    <script src="{{asset('admin/js/jquery-3.7.1.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
    {{-- <script>
        $.ajax({
            type:'get',
            url:`{{route('admin#websiteGet')}}`,
            dataType:'json',
            success:function(data){
                if (data.logo!=null) {
                    $image=`{{asset('storage/website')}}/${data.logo}`;
                    $('#logo-img').attr("src",$image);
                    $('#favicon').attr("href",$image);
                }
            }
        })
    </script> --}}
</html>
