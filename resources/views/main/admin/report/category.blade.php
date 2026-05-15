@extends('main.admin.layout.master')
@section('title')
   Category Report
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
                            <li><a class="dropdown-item" href="{{route('admin#reportCategory',array_merge(request()->all(),['currency'=>$item->currency_code]))}}">{{$item->currency_code}}</a></li>
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
                            <li><a class="dropdown-item" href="{{route('admin#reportCategory',['startDate'=>request('startDate'),'endDate'=>request('endDate'),'shopFilter'=>request('shopFilter')])}}">All</a></li>
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
                    <div class="dropdown">
                        <div class="d-flex align-items-center sale-user-filter">
                            <select name="seller" class="form-control" id="">
                                <option value="">All</option>
                                @foreach ($filterData['seller'] as $item)
                                    <option value="{{$item->name}}" @if ($item->name==request('seller')) selected @endif>{{$item->name}}</option>
                                @endforeach
                            </select>
                            <i class="fa fa-sort-down mb-1" id="method-drop-icon2"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-end gap-1 mb-2">
                <button type="submit" class="btn btn-primary">Apply</button>
                <a href="{{route('admin#reportCategory')}}" class="btn btn-danger">Clear</a>
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
                                <span class=" text-capitalize">{{request('groupBy')??'daily'}}</span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{route('admin#reportCategory',array_merge(request()->all(),['groupBy'=>'daily']))}}">Daily</a></li>
                                <li><a class="dropdown-item" href="{{route('admin#reportCategory',array_merge(request()->all(),['groupBy'=>'weekly']))}}">Weekly</a></li>
                                <li><a class="dropdown-item" href="{{route('admin#reportCategory',array_merge(request()->all(),['groupBy'=>'monthly']))}}">Monthly</a></li>
                                <li><a class="dropdown-item" href="{{route('admin#reportCategory',array_merge(request()->all(),['groupBy'=>'yearly']))}}">Yearly</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="row align-items-center mx-auto">
                        <div class="col-lg-5 mb-5">
                            <div class="d-flex align-items-center justify-content-between">
                                <h6 class="report-logo">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-layers-2"><path d="m16.02 12 5.48 3.13a1 1 0 0 1 0 1.74L13 21.74a2 2 0 0 1-2 0l-8.5-4.87a1 1 0 0 1 0-1.74L7.98 12"/><path d="M13 13.74a2 2 0 0 1-2 0L2.5 8.87a1 1 0 0 1 0-1.74L11 2.26a2 2 0 0 1 2 0l8.5 4.87a1 1 0 0 1 0 1.74Z"/></svg>
                                    <span class="ms-1">Categories</span>
                                </h6>
                            </div>
                            <div class="table-scroll">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Category</th>
                                            <th>Total QTY</th>
                                            <th>Total Price ({{request('currency')??'MMK'}})</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($categories as $item)
                                            <tr>
                                                <td>{{$loop->index+1}}</td>
                                                <td>{{$item->category_name}}</td>
                                                <td>{{$item->total_qty}}</td>
                                                <td>{{$item->total_price}}</td>
                                                <td>
                                                    <a href="{{route('admin#reportCategory',array_merge(request()->all(),['category'=>$item->category_name]))}}" class="text-bg-primary rounded p-2 pt-1" title="Report">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clipboard-minus"><rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M9 14h6"/></svg>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-lg-7 mb-5">
                            <div class="graph-area">
                                <div>
                                    <canvas id="categoryGraph"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="py-2">
                    <div class="graph-area">
                        <div>
                            <canvas id="categoriesGraph"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-excel-btn">
            <table id="salesTable">
                <thead>
                    <tr class="text-end">
                        <th>Caegory Name</th>
                        <th>QTY</th>
                        <th>Total Price({{request('currency')??'MMK'}})</th>
                        <th>Profit({{request('currency')??'MMK'}})</th>
                    </tr>
                </thead>
                <tbody class="py-4">
                    @foreach ($categories as $item)
                        <tr>
                            <td>{{$item['category_name']}}</td>
                            <td>{{$item['total_qty']}}</td>
                            <td>{{number_format($item['total_price'])}}</td>
                            <td>{{number_format($item['total_profit'])}}</td>
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
        $category=@json($categoriesFilterGraph);
        $categoriesGraph=@json($categoriesGraph);

        const ctx = document.getElementById('categoryGraph').getContext('2d');
        charts['categoryGraph'] = new Chart(ctx, chartConfig(chartType, $category.date, $category.total_price, $category.category_name));

        const products=document.getElementById('categoriesGraph').getContext('2d');
        charts['ca$categoriesGraph'] = new Chart(products, chartConfig(chartType, $categoriesGraph.category, $categoriesGraph.total_price, 'Categories'));

    }

    // Initial chart load
    initializeCharts();
</script>
<script>
    $(document).ready(function() {
        $('#salesTable').DataTable({
            dom: 'Bfrtip', // Enable Buttons
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: 'Export to Excel',
                    title: 'Category Report'
                }
            ]
        });
    });
</script>
@endsection
