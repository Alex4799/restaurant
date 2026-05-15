<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Shop;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
public function summary(){
    // filter data
        $startDate = request('startDate')?Carbon::parse(request('startDate'))->startOfDay():Carbon::now('Asia/Yangon')->subDays(29)->startOfDay();
        $endDate = request('endDate')?Carbon::parse(request('endDate'))->endOfDay():Carbon::now('Asia/Yangon')->endOfDay();
        $currency=request('currency')??'MMK';
        $groupBy=request('groupBy')??'daily';

        $shop=Shop::where('active',1)->get();

        $currencies=Currency::get();

        $seller=User::where('position','seller')->get();

        $filterData=[
            'startDate'=>$startDate,
            'endDate'=>$endDate,
            'shop'=>$shop,
            'currencies'=>$currencies,
            'seller'=>$seller,
        ];

    //baseQuery

        $order=Order::whereBetween('created_at',[$startDate,$endDate])
                    ->when(request('shopFilter'),function($query){
                        $query->where('shop_name',request('shopFilter'));
                    })
                    ->where('currency',$currency)
                    ->when(request('seller'),function($query){
                        $query->where('seller_name',request('seller'));
                    });

    //sale data
        $saleData=(clone $order)
                    ->selectRaw("
                        SUM(CASE WHEN status= 1 THEN total_price ELSE 0 END) as grossSale,
                        SUM(CASE WHEN status= 2 THEN total_price ELSE 0 END) as refund,
                        SUM(CASE WHEN status= 1 THEN COALESCE(promotion_price,0) ELSE 0 END) as discount,
                        SUM(CASE WHEN status= 1 THEN total_price-COALESCE(promotion_price,0) ELSE 0 END) as netSale,
                        SUM(CASE WHEN status= 1 THEN profit ELSE 0 END) as profit
                    ")->first();

    //graph data

        $selectQuery=match ($groupBy) {
                            'weekly'=>"WEEK(created_at) as week , YEAR(created_at) as year",
                            'monthly'=>"MONTH(created_at) as month , YEAR(created_at) as year",
                            'yearly'=>"YEAR(created_at) as year",
                            default=>"DATE(created_at) as date",
        };

        $groupByQuery=match ($groupBy) {
                            'weekly'=>['week','year'],
                            'monthly'=>['month','year'],
                            'yearly'=>'year',
                            default=>'date' ,
        };

        $graphOrder=(clone $order)
                    ->selectRaw("
                        $selectQuery,
                        SUM(CASE WHEN status= 1 THEN total_price ELSE 0 END) as grossSale,
                        SUM(CASE WHEN status= 2 THEN total_price ELSE 0 END) as refund,
                        SUM(CASE WHEN status= 1 THEN COALESCE(promotion_price,0) ELSE 0 END) as discount,
                        SUM(CASE WHEN status= 1 THEN COALESCE(tax_price,0) ELSE 0 END) as tax,
                        SUM(CASE WHEN status= 1 THEN total_price - COALESCE(promotion_price,0) ELSE 0 END) as netSale,
                        SUM(CASE WHEN status= 1 THEN profit ELSE 0 END) as profit
                    ")
                    ->groupBy($groupByQuery)
                    ->get();

        $graphData=[
            'date'=>[],
            'grossSale'=>[],
            'discount'=>[],
            'profit'=>[],
            'netSale'=>[],
            'refund'=>[],
        ];

        foreach ($graphOrder as $item) {
            array_push($graphData['grossSale'],$item->grossSale);
            array_push($graphData['refund'],$item->refund);
            array_push($graphData['discount'],$item->discount);
            array_push($graphData['profit'],$item->profit);
            array_push($graphData['netSale'],$item->netSale);
            $date=match ($groupBy) {
                'weekly'=>"$item->year - $item->week",
                'monthly'=>"$item->year - $item->month",
                'yearly'=>"$item->year",
                default=>"$item->date" ,
            };
            array_push($graphData['date'],$date);
        }

        return view('main.admin.report.summary',compact('filterData','saleData','graphData','graphOrder'));
}

public function product(){
    // filter data
        $startDate = request('startDate')?Carbon::parse(request('startDate'))->startOfDay():Carbon::now('Asia/Yangon')->subDays(29)->startOfDay();
        $endDate = request('endDate')?Carbon::parse(request('endDate'))->endOfDay():Carbon::now('Asia/Yangon')->endOfDay();
        $currency=request('currency')??'MMK';
        $groupBy=request('groupBy')??'daily';

        $shop=Shop::where('active',1)->get();

        $currencies=Currency::get();

        $seller=User::where('position','seller')->get();

        $filterData=[
            'startDate'=>$startDate,
            'endDate'=>$endDate,
            'shop'=>$shop,
            'currencies'=>$currencies,
            'seller'=>$seller,
        ];

    // variable

        $selectQuery=match ($groupBy) {
            'weekly'=>"WEEK(order_items.created_at) as week,YEAR(order_items.created_at) as year",
            'monthly'=>"MONTH(order_items.created_at) as month,YEAR(order_items.created_at) as year",
            'yearly'=>"YEAR(order_items.created_at) as year",
            default=>"DATE(order_items.created_at) as date",
        };

        $groupByQuery=match ($groupBy) {
                    'weekly'=>['week','year'],
                    'monthly'=>['month','year'],
                    'yearly'=>'year',
                    default=>'date' ,
        };

    // base query

        $orderItem=OrderItem::leftJoin('orders','order_items.order_id','orders.id')
                            ->whereBetween('order_items.created_at',[$startDate,$endDate])
                            ->where('order_items.currency',$currency)
                            ->when(request('shopFilter'),function($query){
                                $query->where('orders.shop_name',request('shopFilter'));
                            })->when(request('seller'),function($query){
                                $query->where('orders.seller_name',request('seller'));
                            });

    // each order item filter
        $products=(clone $orderItem)
                        ->selectRaw('SUM(order_items.qty) as total_qty,
                        SUM(order_items.price*order_items.qty) as total_price,
                        product_name,SUM(order_items.profit) as total_profit')
                        ->groupBy('product_name')
                        ->get();

        $productName=request('product')??$products->first()?->product_name??'';
        $productsFilter=(clone $orderItem)
                        ->where('product_name',$productName)
                        ->selectRaw("$selectQuery,SUM(order_items.price*order_items.qty) as total_price,product_name")
                        ->groupBy($groupByQuery)
                        ->get();

        $productsFilterGraph=[
            'date'=>[],
            'total_price'=>$productsFilter->pluck('total_price'),
            'product_name'=>$productName
        ];

        foreach ($productsFilter as $item) {
            $date=match ($groupBy) {
                'weekly'=>"$item->year - $item->week",
                'monthly'=>"$item->year - $item->month",
                'yearly'=>"$item->year",
                default=>"$item->date" ,
            };
            array_push($productsFilterGraph['date'],$date);
        }
    // all products report
        $productsGraph=[
            'product'=>$products->pluck('product_name'),
            'total_price'=>$products->pluck('total_price'),
        ];

        return view('main.admin.report.product',compact('filterData','products','productsFilterGraph','productsGraph'));

}

}
