<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Shop;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function summary(){
        dd(request()->all());

    // filter data
        $startDate = Carbon::now('Asia/Yangon')->subDays(29)->format('Y-m-d');
        $endDate = Carbon::now('Asia/Yangon')->format('Y-m-d');

        $shop=Shop::where('active',1)->get();

        $currencies=Currency::get();

        $seller=User::where('position','seller')->get();

        $filterData=[];
        $filterData['startDate']=$startDate;
        $filterData['endDate']=$endDate;
        $filterData['shop']=$shop;
        $filterData['currencies']=$currencies;
        $filterData['seller']=$seller;

        return view('main.admin.report.summary',compact('filterData'));
    }
}
