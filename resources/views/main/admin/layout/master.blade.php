<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="shortcut icon" href="{{asset('admin/image/06cgIF4Qj26l7tdRyV2e6Fo-7.webp')}}" id="favicon" type="image/x-icon">

    <script type="importmap">
        {
            "imports": {
                "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/42.0.2/ckeditor5.js",
                "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/42.0.2/"
            }
        }
    </script>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

    <!-- css bootstrap  -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- font-awesome  -->
    <link rel="stylesheet" href="{{asset('admin/font-awesome/css/all.min.css')}}">

    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="{{asset('admin/flatpickr/flatpickr.min.css')}}">

    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/42.0.2/ckeditor5.css" />

    <!-- moment css  -->
    <link rel="stylesheet" type="text/css" href="{{asset('admin/moment-js/daterangepicker.css')}}" />

    <!-- css -->
    <link rel="stylesheet" href="{{asset('admin/css/style.css')}}?v={{ filemtime(public_path('admin/css/style.css')) }}">

    <script>
        (function () {
            const root = document.documentElement;
            const savedGreen = localStorage.getItem('newGreenColor');
            const saveddarkGray = localStorage.getItem('newdarkGrayColor');

            // Apply colors immediately
            root.style.setProperty('--green', savedGreen);
            root.style.setProperty('--dark-gray', saveddarkGray);
        })();
    </script>

    <script>
        // Apply theme before rendering
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark') {
            document.documentElement.classList.add('dark');
        }
    </script>
    @yield('style')
</head>
<body>

    <section id="{{ $section_id ?? 'profile' }}" class="@if(isset($section_id)) container-fluid @endif">
        <div id="nav">
            <div class="nav-header">
                <h4 class="company_name">Nanobots Store</h4>
                <button class="close-btn"><i class="fa-regular fa-circle-xmark"></i></button>
            </div>
            @include('main.admin.layout.sidebar')
        </div>
        @if (empty($section_id))
            <div class="title-header sticky-top">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <button class="btn btn-toggle" type="button">
                            <i class="fa-solid fa-bars"></i>
                        </button>
                        <h4 class="ms-3">@yield('title')</h4>
                    </div>
                    @if (Auth::user()->position!='waiter')
                        <div>
                            <a href="{{route('admin#kitchenList',['status'=>'0'])}}" class="btn btn-outline-primary position-relative" id="header_kitchen">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chef-hat-icon lucide-chef-hat">
                                    <path d="M17 21a1 1 0 0 0 1-1v-5.35c0-.457.316-.844.727-1.041a4 4 0 0 0-2.134-7.589 5 5 0 0 0-9.186 0 4 4 0 0 0-2.134 7.588c.411.198.727.585.727 1.041V20a1 1 0 0 0 1 1Z"/>
                                    <path d="M6 17h12"/>
                                </svg>
                            </a>
                        </div>
                    @endif
                    @if (Auth::user()->position!='kitchen')
                        <div>
                            <a href="{{route('admin#kitchenList',['status'=>'2'])}}" class="btn btn-outline-primary position-relative" id="header_waiter">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-hand-platter-icon lucide-hand-platter">
                                    <path d="M12 3V2"/>
                                    <path d="m15.4 17.4 3.2-2.8a2 2 0 1 1 2.8 2.9l-3.6 3.3c-.7.8-1.7 1.2-2.8 1.2h-4c-1.1 0-2.1-.4-2.8-1.2l-1.302-1.464A1 1 0 0 0 6.151 19H5"/>
                                    <path d="M2 14h12a2 2 0 0 1 0 4h-2"/>
                                    <path d="M4 10h16"/>
                                    <path d="M5 10a7 7 0 0 1 14 0"/>
                                    <path d="M5 14v6a1 1 0 0 1-1 1H2"/>
                                </svg>
                            </a>
                        </div>
                    @endif
                    <div >
                        <a href="{{route('admin#cartList')}}" class="btn btn-outline-primary position-relative" id="header_cart">
                            <i class="fa-solid fa-cart-shopping"></i>
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <!-- profile start  -->
            @yield('content')
        <!-- profile end  -->
    </section>

</body>

    <!-- js bootstrap  -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- jquery -->
    <script src="{{asset('admin/js/jquery-3.7.1.js')}}"></script>
    <script src="https://js.pusher.com/8.2/pusher.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>

    <!-- Flatpickr JS -->
    <script src="{{asset('admin/flatpickr/flatpickr.min.js')}}"></script>

    <!-- moment js  -->
    <script type="text/javascript" src="{{asset('admin/moment-js/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('admin/moment-js/daterangepicker.min.js')}}"></script>

    <!-- chart js  -->
     <script src="{{asset('admin/chart-js/chart.js')}}"></script>

    <!-- js  -->
    <script src="{{asset('admin/js/script.js')}}?v={{ filemtime(public_path('admin/js/script.js')) }}"></script>
    @if (isset($section_id))
        <script>
            //................................... sale category link active start ........................................
            const saleCategoryLink = document.getElementsByClassName('sale-category-link');
            const productList = document.getElementById('product-list');
            const scanProduct = document.getElementById('scan-product');
            const searchContainer = document.querySelector('.search-container');
            const productCategory = document.querySelector('.product-list-category');

            function saleClick(event, elementId) {
                event.preventDefault();
                const clickSale = event.target;

                if (clickSale.classList.contains('sale-category-active')) {
                    return;
                }

                for (let link of saleCategoryLink) {
                    link.classList.remove('sale-category-active');
                }

                clickSale.classList.add('sale-category-active');
                const clickedIndex = Array.from(saleCategoryLink).indexOf(clickSale);
                localStorage.setItem('currentSaleLink', clickedIndex);

                if (elementId === 'productList') {
                    productList.style.display = 'block';
                    scanProduct.style.display = 'none';
                    searchContainer.style.display = 'flex';
                    productCategory.style.display = 'block';
                } else {
                    scanProduct.style.display = 'block';
                    productList.style.display = 'none';
                    searchContainer.style.display = 'none';
                    productCategory.style.display = 'none';
                }
            }

            window.addEventListener('load', () => {
                const lastClickedIndex = localStorage.getItem('currentSaleLink');
                if (lastClickedIndex !== null) {
                    const lastLink = saleCategoryLink[lastClickedIndex];
                    if (lastLink) {
                        for (let link of saleCategoryLink) {
                            link.classList.remove('sale-category-active');
                        }
                        lastLink.classList.add('sale-category-active');
                        if (lastLink.textContent.trim() === 'Product List') {
                            productList.style.display = 'block';
                            scanProduct.style.display = 'none';
                            searchContainer.style.display = 'flex';
                            productCategory.style.display = 'block';
                        } else if (lastLink.textContent.trim() === 'Scan Barcode') {
                            scanProduct.style.display = 'block';
                            productList.style.display = 'none';
                            searchContainer.style.display = 'none';
                            productCategory.style.display = 'none';
                        }
                    }
                } else {
                    saleCategoryLink[0].classList.add('sale-category-active');
                    productList.style.display = 'block';
                    scanProduct.style.display = 'none';
                    searchContainer.style.display = 'flex';
                    productCategory.style.display = 'block';
                }
            });

            //.................................... sale category link active end . .........................................
            //.................................... add to cart sound effect start ................................
            document.addEventListener('DOMContentLoaded',function(){
            const audio = new Audio("{{asset('admin/sound/beep-07a.mp3')}}");
            const playSound = document.querySelectorAll('.play-sound');
            const soundSwitch = document.getElementById('sound-toggle');

            let soundEnabled = localStorage.getItem('soundEnabled') === 'true';
            if (soundSwitch) {
                soundSwitch.checked = soundEnabled;

                soundSwitch.addEventListener('change',function(){
                soundEnabled = this.checked;
                localStorage.setItem('soundEnabled',soundEnabled);
                });
            }

            playSound.forEach( button => {
                button.addEventListener('click',function(){
                if(soundEnabled){
                    audio.play();
                }
                });
            });


            });
            //.................................... add to cart sound effect end ................................
        </script>
    @endif
    @yield('script')

    <script>
        $.ajax({
            type:'get',
            url:`{{route('admin#getCartData')}}`,
            dataType:'json',
            success:function(data){
                count=data.length;
                $('#header_cart').html(`
                        <i class="fa-solid fa-cart-shopping"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                          <span>${count}</span>
                        </span>
                `);
            }
        })

        function getKitchenData(){
            $.ajax({
                type:'get',
                url:`{{route('admin#getKitchenData')}}`,
                dataType:'json',
                success:function(data){
                    data.forEach(element => {
                        $('#header_kitchen').html(`
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chef-hat-icon lucide-chef-hat">
                                    <path d="M17 21a1 1 0 0 0 1-1v-5.35c0-.457.316-.844.727-1.041a4 4 0 0 0-2.134-7.589 5 5 0 0 0-9.186 0 4 4 0 0 0-2.134 7.588c.411.198.727.585.727 1.041V20a1 1 0 0 0 1 1Z"/>
                                    <path d="M6 17h12"/>
                                </svg>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    <span>${element.orderItemCount}</span>
                                </span>
                        `);
                    });
                }
            })
        }

        function getWaiterData(){
            $.ajax({
                type:'get',
                url:`{{route('admin#getWaiterData')}}`,
                dataType:'json',
                success:function(data){
                    data.forEach(element=>{
                        $('#header_waiter').html(`
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-hand-platter-icon lucide-hand-platter">
                                <path d="M12 3V2"/>
                                <path d="m15.4 17.4 3.2-2.8a2 2 0 1 1 2.8 2.9l-3.6 3.3c-.7.8-1.7 1.2-2.8 1.2h-4c-1.1 0-2.1-.4-2.8-1.2l-1.302-1.464A1 1 0 0 0 6.151 19H5"/>
                                <path d="M2 14h12a2 2 0 0 1 0 4h-2"/>
                                <path d="M4 10h16"/>
                                <path d="M5 10a7 7 0 0 1 14 0"/>
                                <path d="M5 14v6a1 1 0 0 1-1 1H2"/>
                            </svg>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                <span>${element.orderItemCount}</span>
                            </span>
                        `);
                    })
                }
            })
        }

        getKitchenData();
        getWaiterData();

        let shopId={{Auth::user()->shop}};
        let position=`{{Auth::user()->position}}`;

        Pusher.logToConsole = false;

        var pusher = new Pusher('6f1ebfd4aa55bf62ed9a', {
          cluster: 'ap1'
        });

        if (position=='kitchen') {
            var kitchen_channel = pusher.subscribe('kitchen-alert-channel');
            kitchen_channel.bind('kitchen-alert-event', function(data) {
                if (data.shop_id==shopId) {
                    getKitchenData();
                }
            });
        }else if(position=='waiter'){
            var waiter_channel = pusher.subscribe('waiter-alert-channel');
            waiter_channel.bind('waiter-alert-event', function(data) {
                if (data.shop_id==shopId) {
                    getWaiterData();
                }
            });
        }
    </script>

    {{-- <script>
        $.ajax({
            type:'get',
            url:`{{route('admin#getCartData')}}`,
            dataType:'json',
            success:function(data){
                count=data.length;
                $('#header_cart').html(`
                        <i class="fa-solid fa-cart-shopping"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                          <span>${count}</span>
                        </span>
                `);
            }
        })

        $.ajax({
            type:'get',
            url:`{{route('admin#websiteGet')}}`,
            dataType:'json',
            success:function(data){
                $('.company_name').html(data.name);
                if (data.logo!=null) {
                    $image=`{{asset('storage/website')}}/${data.logo}`;
                    $('#favicon').attr("href",$image);
                }
            }
        })
    </script> --}}
</html>
