<div class="nav-body">
    <ul>
        <li class="nav-container report">
            <a href="#" class="nav-content collapsed" data-bs-target="#back-office-dropdown" data-bs-toggle="collapse" aria-expanded="false">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-layout-dashboard"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                </div>
                <span class="ps-3">Report</span>
            </a>
            <ul id="back-office-dropdown" class="px-3 nav-dropdown list-unstyled collapse">
                <li>
                    <a href="{{route('admin#reportSummary')}}" class="nav-link">Summary</a>
                </li>
                <li>
                    <a href="{{route('admin#reportProduct')}}" class="nav-link">Product</a>
                </li>
                <li>
                    <a href="#" class="nav-link">Category</a>
                </li>
                <li>
                    <a href="#" class="nav-link">Seller</a>
                </li>
                <li>
                    <a href="#" class="nav-link">Customer</a>
                </li>
                <li>
                    <a href="#" class="nav-link">Purchase</a>
                </li>
                <li>
                    <a href="#" class="nav-link">Inventory</a>
                </li>
            </ul>
        </li>
        <li class="nav-container adminList">
            <a href="{{route('admin#list')}}" class="nav-content">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
                <span class="ps-3">Admin List</span>
            </a>
        </li>
        <li class="nav-container shop">
            <a href="{{route('admin#shopList')}}" class="nav-content">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-store"><path d="m2 7 4.41-4.41A2 2 0 0 1 7.83 2h8.34a2 2 0 0 1 1.42.59L22 7"/><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><path d="M15 22v-4a2 2 0 0 0-2-2h-2a2 2 0 0 0-2 2v4"/><path d="M2 7h20"/><path d="M22 7v3a2 2 0 0 1-2 2a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 16 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 12 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 8 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 4 12a2 2 0 0 1-2-2V7"/></svg>
                </div>
                <span class="ps-3">Shop</span>
            </a>
        </li>
        <li class="nav-container product">
            <a href="#" class="nav-content collapsed" data-bs-target="#product-dropdown" data-bs-toggle="collapse" aria-expanded="false">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-package-plus"><path d="M16 16h6"/><path d="M19 13v6"/><path d="M21 10V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l2-1.14"/><path d="m7.5 4.27 9 5.15"/><polyline points="3.29 7 12 12 20.71 7"/><line x1="12" x2="12" y1="22" y2="12"/></svg>
                </div>
                <span class="ps-3">Products</span>
            </a>
            <ul id="product-dropdown" class="px-3 nav-dropdown list-unstyled collapse">
                <li>
                    <a href="{{route('admin#categoryList')}}"  class="nav-link">Categories</a>
                </li>
                <li>
                    <a href="{{route('admin#productList')}}"  class="nav-link">Lists</a>
                </li>
            </ul>
        </li>
        <li class="nav-container currency">
            <a href="{{route('admin#currencyList')}}" class="nav-content">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-receipt-cent"><path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z"/><path d="M12 6.5v11"/><path d="M15 9.4a4 4 0 1 0 0 5.2"/></svg>
                </div>
                <span class="ps-3">Currency</span>
            </a>
        </li>
        <li class="nav-container payment">
            <a href="{{route('admin#paymentList')}}" class="nav-content">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-dollar-sign"><line x1="12" x2="12" y1="2" y2="22"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
                <span class="ps-3">Payment Method</span>
            </a>
        </li>
        <li class="nav-container promotion">
            <a href="{{route('admin#promotionList')}}" class="nav-content">
                    <div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-minus"><circle cx="12" cy="12" r="10"/><path d="M8 12h8"/></svg>
                </div>
                <span class="ps-3">Promotion</span>
            </a>
        </li>
        <li class="nav-container tax">
            <a href="{{route('admin#taxList')}}" class="nav-content">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-percent"><line x1="19" x2="5" y1="5" y2="19"/><circle cx="6.5" cy="6.5" r="2.5"/><circle cx="17.5" cy="17.5" r="2.5"/></svg>
                </div>
                <span class="ps-3">Tax</span>
            </a>
        </li>
        <li class="nav-container delivery">
            <a href="{{route('admin#deliveryList')}}" class="nav-content">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-package-open-icon lucide-package-open"><path d="M12 22v-9"/><path d="M15.17 2.21a1.67 1.67 0 0 1 1.63 0L21 4.57a1.93 1.93 0 0 1 0 3.36L8.82 14.79a1.655 1.655 0 0 1-1.64 0L3 12.43a1.93 1.93 0 0 1 0-3.36z"/><path d="M20 13v3.87a2.06 2.06 0 0 1-1.11 1.83l-6 3.08a1.93 1.93 0 0 1-1.78 0l-6-3.08A2.06 2.06 0 0 1 4 16.87V13"/><path d="M21 12.43a1.93 1.93 0 0 0 0-3.36L8.83 2.2a1.64 1.64 0 0 0-1.63 0L3 4.57a1.93 1.93 0 0 0 0 3.36l12.18 6.86a1.636 1.636 0 0 0 1.63 0z"/></svg>
                </div>
                <span class="ps-3">Delivery</span>
            </a>
        </li>
        <li class="nav-container supplier">
            <a href="{{route('admin#supplierList')}}" class="nav-content">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-baggage-claim-icon lucide-baggage-claim"><path d="M22 18H6a2 2 0 0 1-2-2V7a2 2 0 0 0-2-2"/><path d="M17 14V4a2 2 0 0 0-2-2h-1a2 2 0 0 0-2 2v10"/><rect width="13" height="8" x="8" y="6" rx="1"/><circle cx="18" cy="20" r="2"/><circle cx="9" cy="20" r="2"/></svg>
                </div>
                <span class="ps-3">Supplier</span>
            </a>
        </li>
        <li class="nav-container purchase">
            <a href="{{route('admin#purchaseList')}}" class="nav-content">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-truck"><path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/><path d="M15 18H9"/><path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"/><circle cx="17" cy="18" r="2"/><circle cx="7" cy="18" r="2"/></svg>
                </div>
                <span class="ps-3">Purchase</span>
            </a>
        </li>
        <li class="nav-container transfer">
            <a href="{{route('admin#transferList')}}" class="nav-content">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-truck"><path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/><path d="M15 18H9"/><path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"/><circle cx="17" cy="18" r="2"/><circle cx="7" cy="18" r="2"/></svg>
                </div>
                <span class="ps-3">Transfer</span>
            </a>
        </li>
        <li class="nav-container transfer">
            <a href="{{route('admin#reduceList')}}" class="nav-content">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-package-minus-icon lucide-package-minus"><path d="M16 16h6"/><path d="M21 10V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l2-1.14"/><path d="m7.5 4.27 9 5.15"/><polyline points="3.29 7 12 12 20.71 7"/><line x1="12" x2="12" y1="22" y2="12"/></svg>
                </div>
                <span class="ps-3">Reduce</span>
            </a>
        </li>
        <li class="nav-container cart">
            <a href="{{route('admin#cartList')}}" class="nav-content">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-baggage-claim-icon lucide-baggage-claim"><path d="M22 18H6a2 2 0 0 1-2-2V7a2 2 0 0 0-2-2"/><path d="M17 14V4a2 2 0 0 0-2-2h-1a2 2 0 0 0-2 2v10"/><rect width="13" height="8" x="8" y="6" rx="1"/><circle cx="18" cy="20" r="2"/><circle cx="9" cy="20" r="2"/></svg>
                </div>
                <span class="ps-3">Cart</span>
            </a>
        </li>
        <li class="nav-container cart">
            <a href="{{route('admin#orderList')}}" class="nav-content">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-bag"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                </div>
                <span class="ps-3">Order</span>
            </a>
        </li>
        <li class="nav-container cart">
            <a href="{{route('admin#tableListWaiter')}}" class="nav-content">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-baggage-claim-icon lucide-baggage-claim"><path d="M22 18H6a2 2 0 0 1-2-2V7a2 2 0 0 0-2-2"/><path d="M17 14V4a2 2 0 0 0-2-2h-1a2 2 0 0 0-2 2v10"/><rect width="13" height="8" x="8" y="6" rx="1"/><circle cx="18" cy="20" r="2"/><circle cx="9" cy="20" r="2"/></svg>
                </div>
                <span class="ps-3">Selling Dashboard</span>
            </a>
        </li>
        <li class="nav-container cart">
            <a href="{{route('admin#kitchenList')}}" class="nav-content">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chef-hat-icon lucide-chef-hat"><path d="M17 21a1 1 0 0 0 1-1v-5.35c0-.457.316-.844.727-1.041a4 4 0 0 0-2.134-7.589 5 5 0 0 0-9.186 0 4 4 0 0 0-2.134 7.588c.411.198.727.585.727 1.041V20a1 1 0 0 0 1 1Z"/><path d="M6 17h12"/></svg>
                </div>
                <span class="ps-3">Kitchen</span>
            </a>
        </li>
    </ul>
    <div id="nav-bottom">
        <div class="dark-mode py-2">
            <h6 class="mt-1">Dark Mode</h6>
            <label class="toggle-switch" >
                <input type="checkbox" id="theme-toggle">
                <div class="toggle-switch-background">
                  <div class="toggle-switch-handle"></div>
                </div>
            </label>
        </div>
        <form action="{{route('logout')}}" method="POST">
            @csrf
            <button class="d-flex align-items-center bg-transparent text-danger border-0 ms-5 ps-1">
                <i class="fa-solid fa-power-off me-2"></i>
                <h6 class="mt-1">Logout</h6>
            </button>
        </form>
    </div>
</div>
