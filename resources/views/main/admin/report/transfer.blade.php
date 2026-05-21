@extends('main.admin.layout.master')
@section('title')
    Transfer report
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
                                    <li><a class="dropdown-item" href="{{route('admin#reportTransfer',array_merge(request()->all(),['currency'=>$item->currency_code]))}}">{{$item->currency_code}}</a></li>
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
                                    <li><a class="dropdown-item" href="{{route('admin#reportTransfer',array_merge(request()->all(),['startTime'=>null,'endTime'=>null]))}}">All</a></li>
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
                        <a href="{{route('admin#reportTransfer')}}" class="btn btn-danger">Clear</a>
                    </div>
                </form>
                <div class="sale-type-btn">
                    <div class="category-form2">
                        <div class="d-flex align-items-center justify-content-around overflow-x-auto">
                            @foreach ($storeData as $item)
                                <a href="{{route('admin#reportTransfer',array_merge(request()->all(),['store'=>$item['store_id']]))}}" class=" text-decoration-none border border-black p-2">
                                    <h4 class=" text-center">{{$item['store_name']}}</h4>
                                    <h6>Store In - {{number_format($item['storeIn'])}} {{request('currency')??'MMK'}}</h6>
                                    <h6>Store In Additional Fees - {{number_format($item['storeInAdditionalFees'])}} {{request('currency')??'MMK'}}</h6>
                                    <h6>Store Out - {{number_format($item['storeOut'])}} {{request('currency')??'MMK'}}</h6>
                                    <h6>Store Out Additional Fees - {{number_format($item['storeOutAdditionalFees'])}} {{request('currency')??'MMK'}}</h6>
                                </a>
                            @endforeach
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between gap-1 py-1">
                            <h4>{{$storeData[$storeId]['store_name']}}</h4>
                            <div class="dropdown ms-3">
                                <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="text-capitalize">{{request('groupBy')??'daily'}}</span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{route('admin#reportTransfer',array_merge(request()->all(),['groupBy'=>'daily']))}}">Daily</a></li>
                                    <li><a class="dropdown-item" href="{{route('admin#reportTransfer',array_merge(request()->all(),['groupBy'=>'weekly']))}}">Weekly</a></li>
                                    <li><a class="dropdown-item" href="{{route('admin#reportTransfer',array_merge(request()->all(),['groupBy'=>'monthly']))}}">Monthly</a></li>
                                    <li><a class="dropdown-item" href="{{route('admin#reportTransfer',array_merge(request()->all(),['groupBy'=>'yearly']))}}">Yearly</a></li>
                                </ul>
                            </div>
                        </div>
                        <div>
                            <table id="salesTable">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Store In Total Price({{request('currency')??'MMK'}})</th>
                                        <th>Store In Additional Fees({{request('currency')??'MMK'}})</th>
                                        <th>Store Out Total Price({{request('currency')??'MMK'}})</th>
                                        <th>Store Out Additional Fees ({{request('currency')??'MMK'}})</th>
                                    </tr>
                                </thead>
                                <tbody class="py-4">
                                    @foreach($transferFilter as $item)
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
                                            <td>{{$item->receive_total_price}}</td>
                                            <td>{{$item->receive_additional_fees}}</td>
                                            <td>{{$item->send_total_price}}</td>
                                            <td>{{$item->send_additional_fees}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
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
