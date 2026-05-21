<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Shop;
use App\Models\Store;
use App\Models\Transfer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
public function summary(){
    // filter data
    $filterData=$this->filterData();

    //baseQuery

        $order=Order::whereBetween('created_at',[$filterData['startDate'],$filterData['endDate']])
                    ->when(request('shopFilter'),function($query){
                        $query->where('shop_name',request('shopFilter'));
                    })
                    ->where('currency',$filterData['currency'])
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

        $selectQuery=match ($filterData['groupBy']) {
            'weekly'=>"WEEK(orders.created_at) as week,YEAR(orders.created_at) as year",
            'monthly'=>"MONTH(orders.created_at) as month,YEAR(orders.created_at) as year",
            'yearly'=>"YEAR(orders.created_at) as year",
            default=>"DATE(orders.created_at) as date",
        };

        $groupByQuery=$this->groupByQuery($filterData['groupBy']);

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
            $date=$this->formatDateData($filterData['groupBy'],$item);
            array_push($graphData['date'],$date);
        }

        return view('main.admin.report.summary',compact('filterData','saleData','graphData','graphOrder'));
}

public function product(){
    // filter data
        $filterData=$this->filterData();

    // variable

        $selectQuery=$this->selectQuery($filterData['groupBy']);

        $groupByQuery=$this->groupByQuery($filterData['groupBy']);

    // base query

        $orderItem=$this->orderItemBaseQuery($filterData);

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
            $date=$this->formatDateData($filterData['groupBy'],$item);
            array_push($productsFilterGraph['date'],$date);
        }
    // all products report
        $productsGraph=[
            'product'=>$products->pluck('product_name'),
            'total_price'=>$products->pluck('total_price'),
        ];

        return view('main.admin.report.product',compact('filterData','products','productsFilterGraph','productsGraph'));

}

public function category(){
    // filter data
        $filterData=$this->filterData();

    // variable

        $selectQuery=$this->selectQuery($filterData['groupBy']);

        $groupByQuery=$this->groupByQuery($filterData['groupBy']);

    // base query

        $orderItem=$this->orderItemBaseQuery($filterData);

    // each order item filter
        $categories=(clone $orderItem)
                        ->selectRaw('SUM(order_items.qty) as total_qty,
                        SUM(order_items.price*order_items.qty) as total_price,
                        category_name,SUM(order_items.profit) as total_profit')
                        ->groupBy('category_name')
                        ->get();

        $categoryName=request('category')??$categories->first()?->category_name??'';
        $categoriesFilter=(clone $orderItem)
                        ->where('category_name',$categoryName)
                        ->selectRaw("$selectQuery,SUM(order_items.price*order_items.qty) as total_price,category_name")
                        ->groupBy($groupByQuery)
                        ->get();

        $categoriesFilterGraph=[
            'date'=>[],
            'total_price'=>$categoriesFilter->pluck('total_price'),
            'category_name'=>$categoryName
        ];
        foreach ($categoriesFilter as $item) {
            $date=$this->formatDateData($filterData['groupBy'],$item);
            array_push($categoriesFilterGraph['date'],$date);
        }
    // all categories report
        $categoriesGraph=[
            'category'=>$categories->pluck('category_name'),
            'total_price'=>$categories->pluck('total_price'),
        ];

        return view('main.admin.report.category',compact('filterData','categories','categoriesFilterGraph','categoriesGraph'));

}

public function seller(){
    // filter data

        $filterData=$this->filterData();

    // variable

        $selectQuery=match ($filterData['groupBy']) {
            'weekly'=>"WEEK(orders.created_at) as week,YEAR(orders.created_at) as year",
            'monthly'=>"MONTH(orders.created_at) as month,YEAR(orders.created_at) as year",
            'yearly'=>"YEAR(orders.created_at) as year",
            default=>"DATE(orders.created_at) as date",
        };

        $groupByQuery=$this->groupByQuery($filterData['groupBy']);

    // base query

        $order=Order::where('currency',$filterData['currency'])
                    ->whereBetween('created_at',[$filterData['startDate'],$filterData['endDate']])
                    ->when(request('shopFilter'),function($query){
                        $query->where('orders.shop_name',request('shopFilter'));
                    })->where('orders.status',1);

    // each order item filter

        $sellers=(clone $order)
                        ->selectRaw("seller_name,SUM(total_price) as total_price,
                        SUM(subtotal) as subtotal,SUM(profit) as profit, SUM(promotion_price) as promotion_price,SUM(tax_price) as tax_price")
                        ->groupBy('seller_name')
                        ->get();

        $sellerName=request('seller')??$sellers->first()?->seller_name??null;

        $sellerFilter=(clone $order)
                                ->where('seller_name',$sellerName)
                                ->selectRaw("$selectQuery,SUM(total_price) as total_price")
                                ->groupBy($groupByQuery)
                                ->get();

        $sellerFilterGraph=[
            'date'=>[],
            'total_price'=>$sellerFilter->pluck('total_price')->toArray(),
            'seller_name'=>$sellerName??'Other',
        ];

        foreach ($sellerFilter as $item) {
            $date=$this->formatDateData($filterData['groupBy'],$item);
            array_push($sellerFilterGraph['date'],$date);
        }

        $sellerGraph=[
            'seller_name'=>$sellers->pluck('seller_name')??'Other'->toArray(),
            'total_price'=>$sellers->pluck('total_price')->toArray(),
        ];

    return view('main.admin.report.seller',compact('filterData','sellers','sellerFilterGraph','sellerGraph'));
}

public function purchase(){
    //filter data
        $filterData=$this->filterData();

    //variable
        $selectQuery=match ($filterData['groupBy']) {
            'weekly'=>"WEEK(purchases.created_at) as week,YEAR(purchases.created_at) as year",
            'monthly'=>"MONTH(purchases.created_at) as month,YEAR(purchases.created_at) as year",
            'yearly'=>"YEAR(purchases.created_at) as year",
            default=>"DATE(purchases.created_at) as date",
        };

        $groupByQuery=$this->groupByQuery($filterData['groupBy']);

        //all product purchase
        $purchase=Purchase::selectRaw("$selectQuery,SUM(total_price) as total_price")
                        ->whereBetween('purchases.created_at',[$filterData['startDate'],$filterData['endDate']])
                        ->when(request('shopFilter'),function($query){
                            $query->where('purchases.shop_name',request('shopFilter'));
                        })->where('purchases.status',1)
                        ->groupBy($groupByQuery)
                        ->get();

        $purchaseGraph=[
            'date'=>[],
            'total_price'=>$purchase->pluck('total_price')->toArray(),
        ];

        foreach ($purchase as $item) {
            $date=$this->formatDateData($filterData['groupBy'],$item);
            array_push($purchaseGraph['date'],$date);
        }

        $purchaseItem=PurchaseItem::leftJoin('purchases','purchase_items.purchase_id','purchases.id')
                                ->whereBetween('purchases.created_at',[$filterData['startDate'],$filterData['endDate']])
                                ->where('purchase_items.currency',$filterData['currency'])
                                ->when(request('shopFilter'),function($query){
                                    $query->where('purchases.shop_name',request('shopFilter'));
                                })
                                ->where('purchases.status',1);


        $product=(clone $purchaseItem)
                            ->selectRaw('SUM(purchase_items.qty) as qty,SUM(purchase_items.total_price) as total_price,products.name')
                            ->leftJoin('products','purchase_items.product_id','products.id')
                            ->groupBy('purchase_items.product_id')
                            ->get();


        $supplier=(clone $purchaseItem)
                            ->selectRaw('suppliers.name,SUM(purchase_items.total_price) as total_price,SUM(purchase_items.qty) as qty')
                            ->leftJoin('suppliers','purchases.supplier_id','suppliers.id')
                            ->groupBy('purchases.supplier_id')
                            ->get();

        return view('main.admin.report.purchase',compact('filterData','purchaseGraph','product','supplier'));
}

public function transfer(){
    //filter data

        $filterData=$this->filterData();

    //variable

        $selectQuery=match ($filterData['groupBy']) {
            'weekly'=>"WEEK(transfers.created_at) as week,YEAR(transfers.created_at) as year",
            'monthly'=>"MONTH(transfers.created_at) as month,YEAR(transfers.created_at) as year",
            'yearly'=>"YEAR(transfers.created_at) as year",
            default=>"DATE(transfers.created_at) as date",
        };

        $groupByQuery=$this->groupByQuery($filterData['groupBy']);

    //base query
        $transfer=Transfer::where('transfers.status',1)
                            ->whereBetween('transfers.created_at',[$filterData['startDate'],$filterData['endDate']])
                            // ->when(request('shopFilter'),function($query){
                            //     $query->where('shop_name',request('shopFilter'));
                            // })
                            ->where('currency',$filterData['currency']);

    //shop data
        $storeData=[];
        $transferData=(clone $transfer)->get();
        $store=Store::where('active',1)->get();

        foreach ($store as $item) {
            $storeIn=$transferData->where('receive_store_id',$item->id)->sum('total_price');
            $storeOut=$transferData->where('send_store_id',$item->id)->sum('total_price');
            $storeInAdditionalFees=$transferData->where('receive_store_id',$item->id)->sum('additional_fees');
            $storeOutAdditionalFees=$transferData->where('send_store_id',$item->id)->sum('additional_fees');
            $storeData[$item->id]=[
                'store_id'=>$item->id,
                'store_name'=>$item->name,
                'storeIn'=>$storeIn,
                'storeInAdditionalFees'=>$storeInAdditionalFees,
                'storeOut'=>$storeOut,
                'storeOutAdditionalFees'=>$storeOutAdditionalFees
            ];
        }

        // dd($storeData);

        $storeId=request('store')??$store->first()?->id??'';

        $transferFilter=(clone $transfer)
                        ->selectRaw("$selectQuery,
                            SUM(CASE WHEN transfers.send_store_id = $storeId THEN transfers.total_price ELSE 0 END) as send_total_price,
                            SUM(CASE WHEN transfers.send_store_id = $storeId THEN transfers.additional_fees ELSE 0 END) as send_additional_fees,
                            SUM(CASE WHEN transfers.receive_store_id = $storeId THEN transfers.total_price ELSE 0 END) as receive_total_price,
                            SUM(CASE WHEN transfers.receive_store_id = $storeId THEN transfers.additional_fees ELSE 0 END) as receive_additional_fees
                        ")
                        ->where(function($query) use ($storeId){
                            $query->where('transfers.send_store_id',$storeId)
                            ->orWhere('transfers.receive_store_id',$storeId);
                        })
                        ->groupBy($groupByQuery)
                        ->get();

        return view('main.admin.report.transfer',compact('filterData','storeId','storeData','transferFilter'));

}



// private function
private function filterData (){
    $startDate = request('startDate')?Carbon::parse(request('startDate'))->startOfDay():Carbon::now('Asia/Yangon')->subDays(29)->startOfDay();
    $endDate = request('endDate')?Carbon::parse(request('endDate'))->endOfDay():Carbon::now('Asia/Yangon')->endOfDay();
    $currency=request('currency')??'MMK';
    $groupBy=request('groupBy')??'daily';

    $shop=Shop::where('active',1)->get();

    $currencies=Currency::get();

    $seller=User::where('position','seller')->get();

    return [
        'startDate'=>$startDate,
        'endDate'=>$endDate,
        'shop'=>$shop,
        'currencies'=>$currencies,
        'seller'=>$seller,
        'currency'=>$currency,
        'groupBy'=>$groupBy,
    ];
}

private function selectQuery($groupBy){
    return match ($groupBy) {
            'weekly'=>"WEEK(order_items.created_at) as week,YEAR(order_items.created_at) as year",
            'monthly'=>"MONTH(order_items.created_at) as month,YEAR(order_items.created_at) as year",
            'yearly'=>"YEAR(order_items.created_at) as year",
            default=>"DATE(order_items.created_at) as date",
    };
}

private function groupByQuery($groupBy){
    return match ($groupBy) {
                            'weekly'=>['week','year'],
                            'monthly'=>['month','year'],
                            'yearly'=>'year',
                            default=>'date' ,
    };
}

private function orderItemBaseQuery($filterData){
    return OrderItem::leftJoin('orders','order_items.order_id','orders.id')
                            ->whereBetween('order_items.created_at',[$filterData['startDate'],$filterData['endDate']])
                            ->where('order_items.currency',$filterData['currency'])
                            ->when(request('shopFilter'),function($query){
                                $query->where('orders.shop_name',request('shopFilter'));
                            })->when(request('seller'),function($query){
                                $query->where('orders.seller_name',request('seller'));
                            })->where('orders.status',1);
}

private function formatDateData($groupBy,$item){
    return match ($groupBy) {
                'weekly'=>"$item->year - $item->week",
                'monthly'=>"$item->year - $item->month",
                'yearly'=>"$item->year",
                default=>"$item->date" ,
    };
}

}
