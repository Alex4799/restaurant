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
                                    <label for="">Gross Sales (Ks)</label>
                                    <h6>50,000,000</h6>
                                    <div class="sale-plus">
                                        <span>250,000</span>
                                        <span>( +100% )</span>
                                    </div>
                                </a>
                            </div>
                            <div class="sale-type-content">
                                <a href="" class="sale-tag" data-target="refunds">
                                    <label for="">Refunds (Ks)</label>
                                    <h6>20,000</h6>
                                    <div class="sale-minus">
                                        <span>20,000</span>
                                        <span>( +100% )</span>
                                    </div>
                                </a>
                            </div>
                            <div class="sale-type-content">
                                <a href="" class="sale-tag" data-target="sale-discounts">
                                    <label for="">Discounts (Ks)</label>
                                    <h6>450,000</h6>
                                    <div class="sale-plus">
                                        <span>450,000</span>
                                        <span>( -80% )</span>
                                    </div>
                                </a>
                            </div>
                            <div class="sale-type-content">
                                <a href="" class="sale-tag" data-target="net-sales">
                                    <label for="">Net Sales (Ks)</label>
                                    <h6>100,000</h6>
                                    <div class="sale-plus">
                                        <span>100,000</span>
                                        <span>( +100% )</span>
                                    </div>
                                </a>
                            </div>
                            <div class="sale-type-content">
                                <a href="" class="sale-tag" data-target="gross-profit">
                                    <label for="">Gross Profit (Ks)</label>
                                    <h6>500,000</h6>
                                    <div class="sale-plus">
                                        <span>500,000</span>
                                        <span>( +100% )</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <hr>
                        <div class="chart-area">
                            <div id="gross-sales">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h6>Gross Sales</h6>
                                    <div class="dropdown ms-3">
                                        <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            Date
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#">Days</a></li>
                                            <li><a class="dropdown-item" href="#">Weeks</a></li>
                                            <li><a class="dropdown-item" href="#">Months</a></li>
                                            <li><a class="dropdown-item" href="#">Quarters</a></li>
                                            <li><a class="dropdown-item" href="#">Years</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="graph-area">
                                    <div>
                                        <canvas id="myChart"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div id="refunds">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h6>Refunds</h6>
                                    <div class="dropdown ms-3">
                                        <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            Date
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#">Days</a></li>
                                            <li><a class="dropdown-item" href="#">Weeks</a></li>
                                            <li><a class="dropdown-item" href="#">Months</a></li>
                                            <li><a class="dropdown-item" href="#">Quarters</a></li>
                                            <li><a class="dropdown-item" href="#">Years</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="graph-area">
                                    <div>
                                        <canvas id="lineChart"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div id="sale-discounts">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h6>Discounts</h6>
                                    <div class="dropdown ms-3">
                                        <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            Date
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#">Days</a></li>
                                            <li><a class="dropdown-item" href="#">Weeks</a></li>
                                            <li><a class="dropdown-item" href="#">Months</a></li>
                                            <li><a class="dropdown-item" href="#">Quarters</a></li>
                                            <li><a class="dropdown-item" href="#">Years</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="graph-area">
                                    <!-- graph here  -->
                                </div>
                            </div>
                            <div id="net-sales">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h6>Net Sales</h6>
                                    <div class="dropdown ms-3">
                                        <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            Date
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#">Days</a></li>
                                            <li><a class="dropdown-item" href="#">Weeks</a></li>
                                            <li><a class="dropdown-item" href="#">Months</a></li>
                                            <li><a class="dropdown-item" href="#">Quarters</a></li>
                                            <li><a class="dropdown-item" href="#">Years</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="graph-area">
                                    <!-- graph here  -->
                                </div>
                            </div>
                            <div id="gross-profit">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h6>Gross Profit</h6>
                                    <div class="dropdown ms-3">
                                        <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            Date
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#">Days</a></li>
                                            <li><a class="dropdown-item" href="#">Weeks</a></li>
                                            <li><a class="dropdown-item" href="#">Months</a></li>
                                            <li><a class="dropdown-item" href="#">Quarters</a></li>
                                            <li><a class="dropdown-item" href="#">Years</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="graph-area">
                                    <!-- graph here  -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-excel-btn">
                    <div>
                        <a href="" title="Excel Download">
                            <span><i class="fa-solid fa-file-excel me-2"></i>Excel</span>
                        </a>
                    </div>
                </div>
                <div>
                    <table id="table-list2">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Gross Sales(Ks)</th>
                                <th>Refund(Ks)</th>
                                <th>Discounts(Ks)</th>
                                <th>Net Sales(Ks)</th>
                                <th>Cost of goods(Ks)</th>
                                <th>Gross Profit(Ks)</th>
                                <th>Margin</th>
                                <th>Taxes(Ks)</th>
                            </tr>
                        </thead>
                        <tbody class="py-4">
                            <tr class="text-end">
                                <td>2025/02/02</td>
                                <td>100,000</td>
                                <td>100,000</td>
                                <td>0</td>
                                <td>100,000</td>
                                <td>100,000</td>
                                <td>100,000</td>
                                <td>20 %</td>
                                <td>100,000</td>
                            </tr>
                            <tr class="text-end">
                                <td>2025/02/02</td>
                                <td>300,000</td>
                                <td>300,000</td>
                                <td>0</td>
                                <td>300,000</td>
                                <td>300,000</td>
                                <td>300,000</td>
                                <td>60 %</td>
                                <td>300,000</td>
                            </tr>
                            <tr class="text-end">
                                <td>2025/02/01</td>
                                <td>50,000</td>
                                <td>50,000</td>
                                <td>0</td>
                                <td>50,000</td>
                                <td>50,000</td>
                                <td>50,000</td>
                                <td>10 %</td>
                                <td>50,000</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
@endsection
@section('script')
    <script type="text/javascript">
        $(function() {
            var startDate = `{{$filterData['startDate']}}`;
            var endDate = `{{$filterData['endDate']}}`;
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
        const chartConfig = (type, labels, data, barPercentage = 0.5) => ({
            type,
            data: {
                labels,
                datasets: [{
                    label: type === "bar" ? "Sales" : "Refunds",
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

        const ctx = document.getElementById('myChart');
        const ctxLine = document.getElementById("lineChart");

        new Chart(ctx, chartConfig("bar", ['A', 'B', 'C', 'D', 'E'], [12, 19, 3, 5, 10]));
        new Chart(ctxLine, chartConfig("line", ["Jan", "Feb", "Mar", "Apr", "May"], [10, 25, 15, 30, 19]));
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
