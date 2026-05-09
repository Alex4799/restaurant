@extends('main.admin.layout.master')
@section('title')
    Summary report
@endsection
@section('content')
        <div class="table-container py-1">
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
                                Currency - {{request('currency')}}
                            </button>
                            <ul class="dropdown-menu">
                                @foreach ($filterData['currencies'] as $item)
                                    <li><a class="dropdown-item" href="{{route('admin#reportSummary',array_merge(request()->all(),['currency'=>$item->currency_code]))}}">{{$item->currency_code}}</a></li>
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
                                    <li><a class="dropdown-item" href="{{route('admin#reportSummary',array_merge(request()->all(),['startTime'=>null,'endTime'=>null]))}}">All</a></li>
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
                        <a href="{{route('admin#reportSummary')}}" class="btn btn-danger">Clear</a>
                    </div>
                </form>
                <div class="sale-type-btn">
                    <div class="category-form2">
                        <div class="d-flex align-items-center justify-content-between overflow-x-auto">
                            <div class="sale-type-content">
                                <a href="" class="sale-tag" data-target="gross-sales">
                                    <label for="">Gross Sales ({{request('currency')??'MMK'}})</label>
                                    <h6>{{$saleData['grossSale']}}</h6>
                                    <!-- <div class="sale-plus">
                                        <span>250,000</span>
                                        <span>( +100% )</span>
                                    </div> -->
                                </a>
                            </div>
                            <div class="sale-type-content">
                                <a href="" class="sale-tag" data-target="refunds">
                                    <label for="">Refunds ({{request('currency')??'MMK'}})</label>
                                    <h6>{{$saleData['refund']}}</h6>
                                    <!-- <div class="sale-minus">
                                        <span>20,000</span>
                                        <span>( +100% )</span>
                                    </div> -->
                                </a>
                            </div>
                            <div class="sale-type-content">
                                <a href="" class="sale-tag" data-target="sale-discounts">
                                    <label for="">Discounts ({{request('currency')??'MMK'}})</label>
                                    <h6>{{$saleData['discount']}}</h6>
                                    <!-- <div class="sale-plus">
                                        <span>450,000</span>
                                        <span>( -80% )</span>
                                    </div> -->
                                </a>
                            </div>
                            <div class="sale-type-content">
                                <a href="" class="sale-tag" data-target="net-sales">
                                    <label for="">Net Sales ({{request('currency')??'MMK'}})</label>
                                    <h6>{{$saleData['netSale']}}</h6>
                                    <!-- <div class="sale-plus">
                                        <span>100,000</span>
                                        <span>( +100% )</span>
                                    </div> -->
                                </a>
                            </div>
                            <div class="sale-type-content">
                                <a href="" class="sale-tag" data-target="gross-profit">
                                    <label for="">Profit ({{request('currency')??'MMK'}})</label>
                                    <h6>{{$saleData['profit']}}</h6>
                                    <!-- <div class="sale-plus">
                                        <span>500,000</span>
                                        <span>( +100% )</span>
                                    </div> -->
                                </a>
                            </div>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-end gap-1">
                            <div>
                                <select id="chartType" class=" form-control">
                                    <option value="bar">Bar</option>
                                    <option value="line">Line</option>
                                </select>
                            </div>
                            <div class="dropdown ms-3">
                                <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="text-capitalize">{{request('groupBy')??'daily'}}</span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{route('admin#reportSummary',array_merge(request()->all(),['groupBy'=>'daily']))}}">Daily</a></li>
                                    <li><a class="dropdown-item" href="{{route('admin#reportSummary',array_merge(request()->all(),['groupBy'=>'weekly']))}}">Weekly</a></li>
                                    <li><a class="dropdown-item" href="{{route('admin#reportSummary',array_merge(request()->all(),['groupBy'=>'monthly']))}}">Monthly</a></li>
                                    <li><a class="dropdown-item" href="{{route('admin#reportSummary',array_merge(request()->all(),['groupBy'=>'yearly']))}}">Yearly</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="chart-area">
                            <div id="gross-sales">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h6>Gross Sales</h6>
                                </div>
                                <div class="graph-area">
                                    <div>
                                        <canvas id="grossSaleChart"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div id="refunds">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h6>Refunds</h6>
                                </div>
                                <div class="graph-area">
                                    <div>
                                        <canvas id="refundChart"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div id="sale-discounts">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h6>Discounts</h6>
                                </div>
                                <div class="graph-area">
                                    <div>
                                        <canvas id="discountChart"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div id="net-sales">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h6>Net Sales</h6>
                                </div>
                                <div class="graph-area">
                                    <div>
                                        <canvas id="netSaleChart"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div id="gross-profit">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h6>Gross Profit</h6>
                                </div>
                                <div class="graph-area">
                                    <div>
                                        <canvas id="profitChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <table id="salesTable">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Gross Sales({{request('currency')??'MMK'}})</th>
                                <th>Refund({{request('currency')??'MMK'}})</th>
                                <th>Discounts({{request('currency')??'MMK'}})</th>
                                <th>Net Sales({{request('currency')??'MMK'}})</th>
                                <th>Profit({{request('currency')??'MMK'}})</th>
                                <th>Taxes({{request('currency')??'MMK'}})</th>
                            </tr>
                        </thead>
                        <tbody class="py-4">
                            @foreach($graphOrder as $item)
                                <tr class="">
                                    <td>
                                        @if(request('groupBy')=='weekly')
                                            {{$item->year}} - {{$item->week}}
                                        @elseif(request('groupBy')=='monthly')
                                            {{$item->year}} - {{$item->month}}
                                        @elseif(request('groupBy')=='yearly')
                                            {{$item->year}}
                                        @else
                                            {{$item->date}}
                                        @endif
                                    </td>
                                    <td>{{$item->grossSale}}</td>
                                    <td>{{$item->refund}}</td>
                                    <td>{{$item->discount}}</td>
                                    <td>{{$item->netSale}}</td>
                                    <td>{{$item->profit}}</td>
                                    <td>{{$item->tax}}</td>
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
            var startDate = `{{$filterData['startDate']->format('Y-m-d')}}`;
            var endDate = `{{$filterData['endDate']->format('Y-m-d')}}`;
            var start = startDate ? moment(new Date(startDate).toISOString()) : moment().startOf('month');
            var end = endDate ? moment(new Date(endDate).toISOString()) : moment().endOf('month').endOf('day');

            function cb(start, end) {
                $('#reportrange span').html(start.format('MMM D, YYYY') + ' - ' + end.format('MMM D, YYYY'));
                $('#startDate').val(start);
                $('#endDate').val(end);
                console.log('work');

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
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, cb);

            cb(start, end);
        });
    </script>
    <script>
        const chartConfig = (type, title, labels, data, barPercentage = 0.5) => ({
            type,
            data: {
                labels,
                datasets: [{
                    label: title,
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
        let chartType=localStorage.getItem('chartType')??'bar';
        const charts={};
        const graphData=@json($graphData);
        console.log(graphData);
        console.log(graphData.date);
        

        renderChart('grossSaleChart','Gross Sale',graphData['date'],graphData['grossSale']);
        renderChart('refundChart','Refund',graphData['date'],graphData['refund']);
        renderChart('discountChart','Discount',graphData['date'],graphData['discount']);
        renderChart('netSaleChart','Net Sale',graphData['date'],graphData['netSale']);
        renderChart('profitChart','Profit',graphData['date'],graphData['profit']);

        function renderChart(id,title,date,price){
            const chartContainer=document.getElementById(id);
            charts[id]=new Chart(chartContainer, chartConfig(chartType,title, date, price));
        }

        function updateChartType(){
            for (const id in charts) {
                charts[id].config.type=chartType;
                charts[id].update();
                console.log(charts[id].config.type);
                
            }
        }

        $('#chartType').change(function(){
            chartType=$(this).val();
            localStorage.setItem('chartType',chartType);
            updateChartType();
        })

        $('#chartType').val(chartType);
        
    </script>
    <script>
        $(document).ready(function() {
            $('#salesTable').DataTable({
                dom: 'Bfrtip', // Enable Buttons
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: 'Export to Excel',
                        title: 'Summary Report'
                    }
                ]
            });
        });
    </script>
@endsection
