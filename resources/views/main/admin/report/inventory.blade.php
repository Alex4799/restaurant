@extends('main.admin.layout.master')
@section('title')
   Inventory Report
@endsection
@section('style')
    <style>
        .table-scroll{
            height: 50vh;
            overflow-y: scroll;
            margin-top: 5px;
            padding-right: 6px;
        }
    </style>
@endsection
@section('content')
<div class="table-container">
    <div class="table-content">
        <form action="" class="mt-3" method="get" id="sale-filter">
            <div class="d-flex align-items-center flex-wrap mb-2">
                @if (Auth::user()->shop_id==null)
                    <div class="d-flex align-items-center me-2 sale-shop-filter">
                        <select name="shopFilter" class="form-control">
                            <option value="">Filter By Shop</option>
                            @foreach ($shop as $item)
                                <option value="{{$item->name}}" @if ($item->name==request('shopFilter')) selected @endif>{{$item->name}}</option>
                            @endforeach
                        </select>
                        <i class="fa fa-sort-down mb-1" id="method-drop-icon2"></i>
                    </div>
                @endif
                <div class="dropdown">
                    <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Currency - {{$currency}}
                    </button>
                    <ul class="dropdown-menu">
                        @foreach ($currencies as $item)
                            <li><a class="dropdown-item" href="{{route('admin#inventoryReport',array_merge(request()->all(),['currency'=>$item->currency_code]))}}">{{$item->currency_code}}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="mb-2 me-2">
                <div id="reportrange" class="dropdown">
                    <i class="fa-regular fa-calendar me-2"></i>
                    <span></span>
                    <i class="fa fa-sort-down ms-2"></i>
                    <input type="hidden" name="startDate" id="startDate">
                    <input type="hidden" name="endDate" id="endDate">
                </div>
                <div class="d-flex align-items-center">
                    <div class="dropdown">
                        <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" data-bs-auto-close="inside" aria-expanded="false">
                            Time
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{route('admin#inventoryReport',['startDate'=>request('startDate'),'endDate'=>request('endDate'),'shopFilter'=>request('shopFilter')])}}">All</a></li>
                            <li>
                                <a class="dropdown-item custom-btn d-flex align-items-center justify-content-between" href="javascript:void(0);">
                                    Custom
                                    <i class="fa-solid fa-angle-down"></i>
                                </a>
                                <div class="custom-time">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <label for="">Start</label>
                                            <input type="time" name="startTime" value="{{request('startTime')}}" class="start-time">
                                        </div>
                                        <i class="fa-solid fa-minus mt-4"></i>
                                        <div>
                                            <label for="">End</label>
                                            <input type="time" name="endTime" value="{{request('endTime')}}" class="end-time">
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-end gap-1 mb-2">
                <button type="submit" class="btn btn-primary">Apply</button>
                <a href="{{route('admin#inventoryReport')}}" class="btn btn-danger">Clear</a>
            </div>
        </form>
        <div class="sale-type-btn">
            <div class="category-form2">
                <div class="d-flex align-items-center justify-content-end gap-1">
                    <div>
                        <div class="dropdown ms-3">
                            <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                @if (request('groupBy')!=null)
                                    {{request('groupBy')}}
                                @else
                                    Daily
                                @endif
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{route('admin#inventoryReport',array_merge(request()->all(),['groupBy'=>'Daily']))}}">Daily</a></li>
                                <li><a class="dropdown-item" href="{{route('admin#inventoryReport',array_merge(request()->all(),['groupBy'=>'Weekly']))}}">Weekly</a></li>
                                <li><a class="dropdown-item" href="{{route('admin#inventoryReport',array_merge(request()->all(),['groupBy'=>'Monthly']))}}">Monthly</a></li>
                                <li><a class="dropdown-item" href="{{route('admin#inventoryReport',array_merge(request()->all(),['groupBy'=>'Yearly']))}}">Yearly</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div>
                    <div>
                        @foreach ($storeData as $item)
                        <div class="mb-5">
                            <div class="d-flex align-items-center justify-content-center mx-auto mb-2">
                                <h6 class="report-logo2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-landmark-icon lucide-landmark"><line x1="3" x2="21" y1="22" y2="22"/><line x1="6" x2="6" y1="18" y2="11"/><line x1="10" x2="10" y1="18" y2="11"/><line x1="14" x2="14" y1="18" y2="11"/><line x1="18" x2="18" y1="18" y2="11"/><polygon points="12 2 20 7 4 7"/></svg>
                                    <span class="ms-1">{{$item['name']}}</span>
                                </h6>
                            </div>
                            <div class="row inventory-store-table">
                                <div class="col-lg-6 mb-5">
                                    <div class="">
                                        <div class="py-2 mb-4">
                                            <h6 class="text-center text-success">Store In</h6>
                                        </div>
                                        <table class="salesTable">
                                            <thead>
                                                <tr class="text-end">
                                                    <th>#</th>
                                                    <th>Date</th>
                                                    <th>Total Price({{$currency}})</th>
                                                    <th>Additional Fees({{$currency}})</th>
                                                </tr>
                                            </thead>
                                            <tbody class="py-4">
                                                @foreach ($item['income'] as $incomeItem)
                                                    <tr>
                                                        <td>{{$loop->index+1}}</td>
                                                        <td>{{$incomeItem['date']}}</td>
                                                        <td>{{number_format($incomeItem['total_price'])}}</td>
                                                        <td>{{number_format($incomeItem['additional_fee'])}}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-5">
                                    <div class="">
                                        <div class="py-2 mb-4">
                                            <h6 class="text-center text-danger">Store Out</h6>
                                        </div>
                                        <table class="salesTable">
                                            <thead>
                                                <tr class="text-end">
                                                    <th>#</th>
                                                    <th>Date</th>
                                                    <th>Total Price({{$currency}})</th>
                                                    <th>Additional Fees({{$currency}})</th>
                                                </tr>
                                            </thead>
                                            <tbody class="py-4">
                                                @foreach ($item['outcome'] as $outcomeItem)
                                                    <tr>
                                                        <td>{{$loop->index+1}}</td>
                                                        <td>{{$outcomeItem['date']}}</td>
                                                        <td>{{number_format($outcomeItem['total_price'])}}</td>
                                                        <td>{{number_format($outcomeItem['additional_fee'])}}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script type="text/javascript">
    $(function() {
        var startDate = "{{ request('startDate') ?? '' }}";
        var endDate = "{{ request('endDate') ?? '' }}";
        var start = startDate ? moment(new Date(startDate).toISOString()) : moment().startOf('week');
        var end = endDate ? moment(new Date(endDate).toISOString()) : moment(start).add(6, 'days').endOf('day');

        function cb(start, end) {
            $('#reportrange span').html(start.format('MMM D, YYYY') + ' - ' + end.format('MMM D, YYYY'));
            $('#startDate').val(start.format('YYYY-MM-DD'));
            $('#endDate').val(end.format('YYYY-MM-DD'));
        }


        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            singleDatePicker: false,
            linkedCalendars: false,
            showDropdowns: true,
            autoApply: false,
            ranges: {
                'Today': [moment(), moment()],
                'This Week': [moment().startOf('week'), moment().endOf('week')],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'This Year': [moment().startOf('year'), moment().endOf('year')],
            }
        }, cb);

        cb(start, end);
    });
</script>
<script>
    $(document).ready(function() {
        $('.salesTable').DataTable();
    });
</script>
@endsection
