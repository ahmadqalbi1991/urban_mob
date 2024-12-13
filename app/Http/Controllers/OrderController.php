<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Order;
use App\OrderItem;
use App\Invoice;
use App\ShopMembers;
use App\User;
use App\Transection;
use App\Package;
use App\PackageAddons;

use PDF;

class OrderController extends Controller
{   
    public function todayOrders()
    {	
    	
    	$query = DB::table('order_items');
        $query->join('orders', 'orders.id', '=', 'order_items.order_id');
        $query->where('orders.vendor_id',Auth::id());
        $query->where('orders.order_date',Carbon::today());
        $query->groupBy('shop_item_id')->selectRaw('shop_item_id,item_id,item_name,item_brand,item_unit,item_price,sum(item_qty) as total_qty,item_icon');
        $order_items= $query->get();
        //dd($order_items);

     	$query=Order::whereDate('order_date', Carbon::today());
        $query->where('vendor_id',Auth::id());
        $orders=$query->with('items')->with('customer')->get();

        //dd($orders);

        $todayOrders=todayOrdersCount(Auth::id());

        return view('shop.today_orders',compact('orders','order_items','todayOrders'));
    }
    public function order_status_update($request_id,$status)
    {
        $request = Order::find($request_id);
        $request->order_status = $status;
        $res=$request->save();
        if($res){
            return redirect()->back()
                        ->with('success','Request status changed successfully.');
        }
        else{
            return redirect()->route('today.orders')
                        ->with('error','Something is wrong, Try Later.');
        }
    }
    public function allOrders(Request $request)
    {   
        
        $start="";$end="";

        //dd($request);
        if($request->start_date && $request->end_date)
        {
            $this->validate($request,[
               'start_date' => 'required|date|before_or_equal:end_date',
               'end_date' => 'required|date',
            ]);

            $start = Carbon::parse($request->start_date);
            $end = Carbon::parse($request->end_date);

        }
        $query=Order::orderBy('id','DESC');
        if($request && $start && $end)
        {
            $query->whereDate('order_date','<=',$end->format('y-m-d'))->whereDate('order_date','>=',$start->format('y-m-d'));
        }
        $orders=$query->with('items')->with('customer','vendor')->paginate(10);
        //dd($orders);
        return view('all_orders',compact('orders','request'));
    }
    public function myOrders(Request $request)
    {   
        $start="";$end="";

        //dd($request);

        if($request->start_date && $request->end_date)
        {
            $this->validate($request,[
               'start_date' => 'required|date|before_or_equal:end_date',
               'end_date' => 'required|date',
            ]);

            $start = Carbon::parse($request->start_date);
            $end = Carbon::parse($request->end_date);


        }
        $query=Order::where('vendor_id',Auth::id());
        if($request && $start && $end)
        {
            $query->whereDate('order_date','<=',$end->format('y-m-d'))->whereDate('order_date','>=',$start->format('y-m-d'));
        }
        $orders=$query->with('items')->with('customer')->orderBy('id','DESC')->paginate(10);
        //dd($orders);
        return view('shop.my_orders',compact('orders','request'));
    }
    public function generateInvoice(Request $request)
    {
        $customers="";
        if($request->month && $request->year)
        {
            $request->validate([
                //'customer' => 'required',
                'month' => 'required',
                'year' => 'required'
            ]);
            $query=Order::where('vendor_id',Auth::id())->whereMonth('order_date',$request->month)->whereYear('order_date', $request->year)->select('customer_id')->distinct();
            $customers=$query->with('customer')->get();
            if($customers)
            {
                foreach ($customers as $key => $value) {
                    $invoice=Invoice::where('vendor_id',Auth::id())->where('customer_id',$value->customer_id)->where('month',$request->month)->where('year', $request->year)->first();
                    if($invoice)
                    {
                        $value->invoice=$invoice->id;
                    }
                    else
                    {
                        $value->invoice=0;
                    }
                }
            }
            //dd($customers);
        }
        else
        {
           $customers="";
           $request->month=Carbon::today()->subMonths(1)->format('m');
           $request->year=Carbon::today()->subMonths(1)->format('Y');
        }
        $months=[
                    ["id"=>'01',"label"=>'Jan'],
                    ["id"=>'02',"label"=>'Feb'],
                    ["id"=>'03',"label"=>'Mar'],
                    ["id"=>'04',"label"=>'Apr'],
                    ["id"=>'05',"label"=>'May'],
                    ["id"=>'06',"label"=>'Jun'],
                    ["id"=>'07',"label"=>'Jul'],
                    ["id"=>'08',"label"=>'Aug'],
                    ["id"=>'09',"label"=>'Sep'],
                    ["id"=>'10',"label"=>'Oct'],
                    ["id"=>'11',"label"=>'Nov'],
                    ["id"=>'12',"label"=>'Dec']
                ];
        return view('shop.invoice_generate',compact('customers','months','request'));
    }
    public function searchOrder(Request $request)
    {
       
            $query = DB::table('order_items');
            $query->join('orders', 'orders.id', '=', 'order_items.order_id');
            $query->where('orders.order_status','Delivered');
            $query->where('orders.customer_id',$request->customer_id);
            $query->where('orders.vendor_id',Auth::id());
            $query->whereMonth('orders.order_date',$request->month);
            $query->whereYear('orders.order_date',$request->year);
            $query->groupBy('item_name','item_brand','item_unit')->selectRaw('item_id,item_name,item_brand,item_unit,item_price,sum(item_qty) as total_qty,sum(item_total) as total_amount,item_icon');
            $orders= $query->get();
        //print_r($orders);
            $customer = User::find($request->customer_id);
            $returnHTML = view('html.bill',compact('orders','customer','request'))->render();
            return response()->json(array('success' => true, 'html'=>$returnHTML));
    }
    public function invoiceSave(Request $request)
    {   
        
        if($request->month==Carbon::today()->format('m') && $request->year==Carbon::today()->format('Y'))
        {
            return response()->json(array('success' => false, 'html'=>"", 'msg'=>"Current month not end, please generate bill after month end"));
        }
        else
        {
            $pending_orders=Order::where('vendor_id',Auth::id())->where('customer_id',$request->customer_id)->whereMonth('order_date',$request->month)->whereYear('order_date', $request->year)->where('order_status','Pending')->count(); 

            if($pending_orders==0)
            {
                $query=Order::where('vendor_id',Auth::id())->where('customer_id',$request->customer_id)->whereMonth('order_date',$request->month)->whereYear('order_date', $request->year)->where('order_status','Delivered');
                $query->groupBy('customer_id','vendor_id')->selectRaw('vendor_id,customer_id,sum(total_amount) as final_amount');
                $order= $query->first();

                $invoiceId=0;
                if($order)
                {

                    $input=[
                            'customer_id' => $request->customer_id,
                            'vendor_id'=> Auth::id(),
                            'month'=>$request->month,
                            'year'=>$request->year,
                            'amount'=>$order->final_amount,
                        ];

                    $invoice=Invoice::where('vendor_id',Auth::id())->where('customer_id',$request->customer_id)->where('month',$request->month)->where('year', $request->year)->first();
                    if($invoice)
                    {
                        $res=Invoice::where('id',$invoice->id)->update($input);
                        $invoiceId=$invoice->id;
                    }
                    else
                    {
                        $invoiceId=Invoice::create($input)->id;
                        $bill_input=[
                                    'customer_id' => $request->customer_id,
                                    'vendor_id'=> Auth::id(),
                                    'amount'=>$order->final_amount,
                                    'remark'=>'Bill ('.$request->month.'-'.$request->year.')',
                                    'type'=>'Cr'
                                ];
                        Transection::create($bill_input);
                        updateWallet(Auth::id(),$request->customer_id,$order->final_amount,'Cr');
                    }

                    $customer = User::find($request->customer_id);
                    $returnHTML = view('html.invoice',compact('order','customer','request','invoiceId'))->render();
                    return response()->json(array('success' => true, 'html'=>$returnHTML));

                }
                else
                {
                    return response()->json(array('success' => false, 'html'=>"", 'msg'=>"No Orders Found"));
                }
                
            }
            else
            {
                return response()->json(array('success' => false, 'html'=>"", 'msg'=>"Please update pending order status first"));
            }
        }
        
    }
    public function allInvoices(Request $request)
    {
    	$month="";$year="";
        //dd($request);
        if($request->month && $request->year)
        {
            $this->validate($request,[
               'month' => 'required',
               'year' => 'required',
            ]);

            $month = $request->month;
            $year = $request->year;

        }
        $query=Invoice::orderBy('id','DESC');
        if($request && $month && $year)
        {
            $query->where('month',$month)->where('year',$year);
        }
        $invoices=$query->with('customer','vendor')->paginate(10);
        $months=Invoice::distinct()->pluck('month');
        $years=Invoice::distinct()->pluck('year');

        return view('all_invoices',compact('invoices','months','years','request'));
    }
    public function orderInvoice(Request $request)
    {
        
        $month="";$year="";
        //dd($request);
        if($request->month && $request->year)
        {
            $this->validate($request,[
               'month' => 'required',
               'year' => 'required',
            ]);

            $month = $request->month;
            $year = $request->year;

        }
        $query=Invoice::where('vendor_id',Auth::id());
        if($request && $month && $year)
        {
            $query->where('month',$month)->where('year',$year);
        }
        $invoices=$query->with('customer','vendor')->orderBy('id','DESC')->paginate(10);
        $months=Invoice::where('vendor_id',Auth::id())->distinct()->pluck('month');
        $years=Invoice::where('vendor_id',Auth::id())->distinct()->pluck('year');

        return view('shop.order_invoice',compact('invoices','months','years','request'));
    }
    public function todayOrdersGenerate()
    {       

        Order::where('vendor_id',Auth::id())->whereDate('order_date', Carbon::today())->delete();
        $result=false;
        $orders=todayOrders(Auth::id());
        if($orders['packages'] || $orders['packageAddons'])
        { 
            $data=[];
            if($orders['packages'])
            {
                foreach ($orders['packages'] as $key => $value) {
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
            if($orders['packageAddons'])
            {
                foreach ($orders['packageAddons'] as $key => $value) {
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
            if($result)
            {
                return redirect()->back()
                    ->with('success','Today Orders Generated.');
            }
            else
            {
                return redirect()->back()
                    ->with('error','Something is wrong, Try Later.');
            }
        }
        else
        {
            return redirect()->back()
                    ->with('error','No active subscription package you have.');
        }

    }
    public function payment_save(Request $request,$customer_id)
    {

        $request->validate([
            'amount' => 'required|numeric',
            'remark' => 'required|string'
        ]);

        $input=[
                    'customer_id' => $customer_id,
                    'vendor_id'=> Auth::id(),
                    'amount'=>$request->amount,
                    'remark'=>$request->remark,
                    'type'=>'Dr'
                ];

        $res=Transection::create($input);
        if($res)
        {
            updateWallet(Auth::id(),$customer_id,$request->amount,'Dr');
            return redirect()->back()
                        ->with('success','Payment received successfully.');
        }
        else
        {
            return redirect()->back()
                        ->with('error','Something is wrong, Try Later.');
        }
        
    }
    public function invoiceDownload(Request $request)
    {

        $invoice=Invoice::where('id',$request->id)->with('customer','vendor')->first();

        if($invoice)
        {   
            $query = DB::table('order_items');
            $query->join('orders', 'orders.id', '=', 'order_items.order_id');
            $query->where('orders.order_status','Delivered');
            $query->where('orders.customer_id',$invoice->customer_id);
            $query->where('orders.vendor_id',$invoice->vendor_id);
            $query->whereMonth('orders.order_date',$invoice->month);
            $query->whereYear('orders.order_date',$invoice->year);
            $query->groupBy('item_name','item_brand','item_unit')->selectRaw('item_id,item_name,item_brand,item_unit,item_price,sum(item_qty) as total_qty,sum(item_total) as total_amount,item_icon');
            $orders= $query->get();
           

                $params = ['orders' => $orders, 'invoice' => $invoice];
                view()->share($params);

                if($request->has('download')){
                    $pdf = PDF::loadView('html.invoice_pdf',$params);
                    return $pdf->download('invoice-'.$invoice->id.'.pdf');
                }

                return view('html.invoice_pdf');  

        }

    }
    function order_delivered($today)
    {   
        $query=Order::whereDate('order_date', Carbon::today());
        $query->where('vendor_id',Auth::id());
        if($today=='Yes')
        {   

            $res=Order::whereDate('order_date', Carbon::today())->where('vendor_id',Auth::id())->where('order_status','Pending')->update(['order_status' => 'Delivered']);

        }
        else
        {
            $res=Order::where('vendor_id',Auth::id())->where('order_status','Pending')->update(['order_status' => 'Delivered']);
        }

        if($res){
            return redirect()->back()
                        ->with('success','All orders delevered successfully.');
        }
        else{
            return redirect()->back()
                        ->with('error','Something is wrong, Try Later.');
        }
    }
}
