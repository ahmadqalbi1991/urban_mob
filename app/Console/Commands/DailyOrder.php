<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Package;
use App\PackageItem;
use App\PackageLeave;
use App\PackageAddons;
use App\PackageAddonItems;
use App\Item;
use App\Order;
use App\OrderItem;

class DailyOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Respectively generate order daily ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $query=Package::where('is_active',1)->where('package_status','Accept');
            $query->where(function ($query) {
                $query->where('package_type','Daily');
                $query->whereDate('start_date','<=', Carbon::today());
            });
            $query->orWhere(function ($query) {
                $query->where('package_type','Weekly');
                $query->whereDate('start_date','<=', Carbon::today());
                $query->where('week_day', Carbon::today()->format('l'));
            });
            $query->orWhere(function ($query) {
                $query->where('package_type','Alternate');
                $query->whereDate('start_date','<=', Carbon::today());
                $query->where('week_day', Carbon::today()->format('l'));
            });
            $packages=$query->with('items')->get();
            $packageAddons=PackageAddons::whereDate('addon_date',Carbon::today())->where('status','Accept')->with('items')->get();
            if($packages || $packageAddons){ 
                $result=false;
                $data=[];
                if($packages)
                {
                    foreach ($packages as $key => $value) {
                        $data=array(
                        "package_id"=>$value->id,
                        "vendor_id"=>$value->vendor_id,
                        "customer_id"=>$value->customer_id,
                        "order_status"=>'Pending',
                        "order_date"=>Carbon::today(),
                        "total_amount"=>0,
                        );

                        $lastId=Order::create($data)->id;
                        if($lastId){ 
                                $result=true;
                                $items=array();
                                $total=0;
                                foreach($value->items as $item)
                                {       
                                    $total+=$item->qty*$item->shopItem->price;
                                    $items[]=[
                                            'order_id'=>$lastId,
                                            'shop_item_id'=>$item->shopItem->id,
                                            'item_id'=>$item->shopItem->item_id,
                                            'item_name'=>$item->shopItem->item->name,
                                            'item_brand'=>$item->shopItem->item->brand,
                                            'item_unit'=>$item->shopItem->quantity.' '.$item->shopItem->unit,
                                            'item_qty'=>$item->qty,
                                            'item_price'=>$item->shopItem->price,
                                            'item_total'=>$item->qty*$item->shopItem->price,
                                            'item_icon'=>$item->shopItem->item->icon
                                        ];
                                    
                                }
                                OrderItem::insert($items);
                                Order::where('id', $lastId)->update(['total_amount' => $total]);
                                if($value->package_type=='Alternate')
                                {
                                    Package::where('id', $value->id)->update(['week_day' => Carbon::now()->addDays(2)->format('l')]);
                                }
                        } 
                    }
                }

                if($packageAddons)
                {
                    foreach ($packageAddons as $key => $value) {
                        $data=array(
                        "package_id"=>$value->id,
                        "vendor_id"=>$value->vendor_id,
                        "customer_id"=>$value->customer_id,
                        "order_status"=>'Pending',
                        "order_date"=>Carbon::today(),
                        "total_amount"=>0,
                        "is_extra_order"=>1
                        );

                        $lastId=Order::create($data)->id;
                        if($lastId){ 
                                $result=true;
                                $items=array();
                                $total=0;
                                foreach($value->items as $item)
                                {   
                                    $total+=$item->qty*$item->shopItem->price;
                                    $items[]=[
                                            'order_id'=>$lastId,
                                            'shop_item_id'=>$item->shopItem->id,
                                            'item_id'=>$item->shopItem->item_id,
                                            'item_name'=>$item->shopItem->item->name,
                                            'item_brand'=>$item->shopItem->item->brand,
                                            'item_unit'=>$item->shopItem->quantity.' '.$item->shopItem->unit,
                                            'item_qty'=>$item->qty,
                                            'item_price'=>$item->shopItem->price,
                                            'item_total'=>$item->qty*$item->shopItem->price,
                                            'item_icon'=>$item->shopItem->item->icon
                                        ];
                                }
                                OrderItem::insert($items);
                                Order::where('id', $lastId)->update(['total_amount' => $total]);
                        } 
                    }
                }
        }

        $this->info('Successfully generate order.');
    }
}
