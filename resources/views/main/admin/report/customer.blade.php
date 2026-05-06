@extends('main.admin.layout.master')
@section('title')
   Customer Report
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
                        @foreach ($shop as $item)
                            <option value="{{$item->name}}" @if ($item->name==request('shopFilter')) selected @endif>{{$item->name}}</option>
                        @endforeach
                    </select>
                    <i class="fa fa-sort-down mb-1" id="method-drop-icon2"></i>
                </div>
                <div class="dropdown dropdown-menu-end">
                    <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Currency - {{$currency}}
                    </button>
                    <ul class="dropdown-menu">
                        @foreach ($currencies as $item)
                            <li><a class="dropdown-item" href="{{route('admin#categoryReport',array_merge(request()->all(),['currency'=>$item->currency_code]))}}">{{$item->currency_code}}</a></li>
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
                            <li><a class="dropdown-item" href="{{route('admin#customerReport',['startDate'=>request('startDate'),'endDate'=>request('endDate'),'shopFilter'=>request('shopFilter')])}}">All</a></li>
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
                <a href="{{route('admin#categoryReport')}}" class="btn btn-danger">Clear</a>
            </div>
        </form>
        <div class="sale-type-btn">
            <div class="category-form2 py-4 overflow-auto">
                <table id="salesTable">
                    <thead>
                        <tr class="text-end">
                            <th>User Name</th>
                            <th>User Email</th>
                            <th>User Phone</th>
                            <th>Subtotal</th>
                            <th>Profit({{$currency}})</th>
                            <th>Promotion Price({{$currency}})</th>
                            <th>Tax Price({{$currency}})</th>
                            <th>Total Price({{$currency}})</th>
                        </tr>
                    </thead>
                    <tbody class="py-4">
                        @foreach ($customer as $item)
                            <tr>
                                <td>
                                    @if ($item['user_name']!=null)
                                        {{$item['user_name']}}
                                    @else
                                        Unknown
                                    @endif
                                </td>
                                <td>
                                    @if ($item['user_email']!=null)
                                        {{$item['user_email']}}
                                    @else
                                        Unknown
                                    @endif
                                </td>
                                <td>
                                    @if ($item['user_phone']!=null)
                                        {{$item['user_phone']}}
                                    @else
                                        Unknown
                                    @endif
                                </td>
                                <td>{{number_format($item['subtotal'])}}</td>
                                <td>{{number_format($item['total_profit'])}}</td>
                                <td>{{number_format($item['promotion_price'])}}</td>
                                <td>{{number_format($item['tax_price'])}}</td>
                                <td>{{number_format($item['total_price'])}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
        $('#salesTable').DataTable({
            dom: 'Bfrtip', // Enable Buttons
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: 'Export to Excel',
                    title: 'Customer Report'
                }
            ]
        });
    });
</script>
@endsection
