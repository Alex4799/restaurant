@extends('main.admin.layout.master')
@section('title')
   Purchase Report
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
                <div class="d-flex align-items-center me-2 sale-shop-filter">
                    <select name="shopFilter" class="form-control">
                        <option value="">Filter By Shop</option>
                        @foreach ($filterData['shop'] as $item)
                            <option value="{{$item->name}}" @if ($item->name==request('shopFilter')) selected @endif>{{$item->name}}</option>
                        @endforeach
                    </select>
                    <i class="fa fa-sort-down mb-1" id="method-drop-icon2"></i>
                </div>
                <div class="dropdown dropdown-menu-end">
                    <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Currency - {{request('currency')??'MMK'}}
                    </button>
                    <ul class="dropdown-menu">
                        @foreach ($filterData['currencies'] as $item)
                            <li><a class="dropdown-item" href="{{route('admin#reportPurchase',array_merge(request()->all(),['currency'=>$item->currency_code]))}}">{{$item->currency_code}}</a></li>
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
                            <li><a class="dropdown-item" href="{{route('admin#reportPurchase',['startDate'=>request('startDate'),'endDate'=>request('endDate'),'shopFilter'=>request('shopFilter')])}}">All</a></li>
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
                <a href="{{route('admin#reportPurchase')}}" class="btn btn-danger">Clear</a>
            </div>
        </form>
         <div class="sale-type-btn">
            <div class="category-form2">
                <div class="d-flex align-items-center justify-content-end gap-1">
                    <div>
                        <div class="d-flex align-items-center sale-line-filter">
                            <select name="" id="chartType" class="form-control">
                                <option value="line">Line</option>
                                <option value="bar">Bar</option>
                            </select>
                            <i class="fa fa-sort-down mb-1" id="method-drop-icon2"></i>
                        </div>
                    </div>
                    <div>
                        <div class="dropdown ms-2">
                            <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                @if (request('groupBy')!=null)
                                    <span class=" text-capitalize">{{request('groupBy')}}</span>
                                @else
                                    Daily
                                @endif
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{route('admin#reportPurchase',array_merge(request()->all(),['groupBy'=>'daily']))}}">Daily</a></li>
                                <li><a class="dropdown-item" href="{{route('admin#reportPurchase',array_merge(request()->all(),['groupBy'=>'weekly']))}}">Weekly</a></li>
                                <li><a class="dropdown-item" href="{{route('admin#reportPurchase',array_merge(request()->all(),['groupBy'=>'monthly']))}}">Monthly</a></li>
                                <li><a class="dropdown-item" href="{{route('admin#reportPurchase',array_merge(request()->all(),['groupBy'=>'yearly']))}}">Yearly</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="d-flex align-items-center justify-content-between">
                        <h6>Purchase</h6>
                    </div>
                    <div>
                        <div class="graph-area">
                            <div>
                                <canvas id="purchaseGraph"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-excel-btn">
            <table class="salesTable">
                <thead>
                    <tr class="text-end">
                        <th>Product Name</th>
                        <th>Qty</th>
                        <th>Total Price({{request('currency')??'MMK'}})</th>
                    </tr>
                </thead>
                <tbody class="py-4">
                    @foreach ($product as $item)
                        <tr>
                            <td>{{$item['name']}}</td>
                            <td>{{number_format($item['qty'])}}</td>
                            <td>{{number_format($item['total_price'])}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="table-excel-btn">
            <table class="salesTable">
                <thead>
                    <tr class="text-end">
                        <th>Supplier Name</th>
                        <th>Qty</th>
                        <th>Total Price({{request('currency')??'MMK'}})</th>
                    </tr>
                </thead>
                <tbody class="py-4">
                    @foreach ($supplier as $item)
                        <tr>
                            <td>{{$item['name']}}</td>
                            <td>{{number_format($item['qty'])}}</td>
                            <td>{{number_format($item['total_price'])}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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
    let chartType =localStorage.getItem('chartType') ?? 'bar';
    let charts = {}; // Store chart instances

    $('#chartType').change(function(){
        chartType = $(this).val(); // Update the chart type
        localStorage.setItem('chartType',chartType);
        updateChartType();
    });

    $('#chartType').val(chartType);

    const chartConfig = (type, labels, data, label, barPercentage = 0.5) => ({
        type,
        data: {
            labels,
            datasets: [{
                label,
                data,
                backgroundColor: "rgba(26, 164, 58, 0.668)",
                borderColor: "rgb(42, 145, 42)",
                borderWidth: 1,
                ...(type === "line" && { pointBackgroundColor: "rgba(26, 164, 58, 0.668)" }),
                ...(type === "bar" && { barPercentage })
            }]
        },
        options: {
            scales: {
                x: { ticks: { color: "#8d8d8d" }, grid: { color: "#a4a4a427" } },
                y: { ticks: { color: "#8d8d8d" }, grid: { color: "#a4a4a427" } }
            }
        }
    });

    function updateChartType() {
        for (let id in charts) {
            charts[id].config.type = chartType; // Change the type (bar -> line or line -> bar)
            charts[id].update(); // Update the chart with new type
        }
    }

    function initializeCharts() {
        $purchase=@json($purchaseGraph);

        const ctx = document.getElementById('purchaseGraph').getContext('2d');
        charts['purchaseGraph'] = new Chart(ctx, chartConfig(chartType, $purchase.date, $purchase.total_price, 'Purchase'));
    }

    // Initial chart load
    initializeCharts();
</script>
<script>
    $(document).ready(function() {
        $('.salesTable').DataTable();
    });
</script>
@endsection
