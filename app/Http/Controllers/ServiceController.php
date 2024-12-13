<?php

namespace App\Http\Controllers;

use Auth;
use App\Addon;
use App\Coupon;
use App\Service;
use App\Category;
use App\Attribute;
use App\ChildCategory;
use App\AttributeValue;
use App\ServiceGallery;
use App\ServiceAttributeValue;
use App\ServiceAttributeValueItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Service::select('*');

        if($request->search)

        {            

            $query->where(function($query) use ($request){

                $query->where('name', 'LIKE', '%'.$request->search.'%');

                      // ->orWhere('brand_id', 'LIKE', '%'.$request->search.'%');

            });

        }

        $services = $query->orderBy('id','DESC')->get();

        return view('service.index',compact('services','request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categorys = Category::where('status','1')->where('parent_id','0')->get();

        $addons = Addon::all();

        $attribute = Attribute::all();

        return view('service.create',compact('categorys','addons','attribute'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request,[
            'name'              => 'required',
        ]);

        if($request->hasFile('image')){

            $imageName = time().'.'.$request->image->extension(); 
            $path = $request->image->move(public_path('/uploads/service/'), $imageName);
            $params['thumbnail_img'] = $imageName;

        }

        if($request->hasFile('featured_banner')){

            $imageName = time().'.'.$request->featured_banner->extension(); 
            $path = $request->featured_banner->move(public_path('/uploads/service/featured_banner/'), $imageName);
            $params['featured_banner'] = $imageName;

        }
 
        $params['user_id']              = Auth::user()->id;

        $params['addon_id']             = $request->addon_id;

        $params['parent_id']            = $request->parent_id;

        $params['slug']                 = Str::slug($request->name);

        $params['name']                 = $request->name;

        $params['price']                = $request->price?$request->price:'0';

        $params['material_status']      = $request->material_status=='on'?'True':'False';

        $params['material_price']       = $request->material_price?$request->material_price:'0';

        $params['status']               = $request->status=='on'?'1':'0';

        $params['featured']             = $request->featured=='on'?'1':'0';

        $params['recommended']          = $request->recommended;

        $params['short_description']    = $request->short_description;

        $params['description']          = $request->description;

        $params['meta_title']           = $request->meta_title;

        $params['meta_description']     = $request->meta_description;

        $params['canonical']            = $request->canonical;

        $params['um_commission']        = $request->um_commission;

        $service = Service::create($params);
        
        if($request->hasFile('gallery')){

            // foreach ($request->gallery as $key => $gallery) {
            //     $imageName = $service->name.'_'.time().'.'.$gallery->extension(); 
            //     $path = $gallery->move(public_path('/uploads/service/gallery/'), $imageName);
            //     $params_g['photos'] = $imageName;
            //     $params_g['service_id'] = $service->id;
                
            //     ServiceGallery::create($params_g);
            // }
            $imageName = $service->name.'_'.time().'.'.$request->gallery->extension(); 
            $path = $request->gallery->move(public_path('/uploads/service/gallery/'), $imageName);
            $params_g['photos'] = $imageName;
            $params_g['service_id'] = $service->id;
        
            ServiceGallery::create($params_g);


        }
    
        if($service){

            return redirect('service')->with('success','Service created successfully.');

        } else {

            return redirect('service')->with('error','Something want wrong.');

        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function show(Service $service)
    {
        $categorys = Category::where('status',1)->get();

        $child_category = ChildCategory::where('status','1')->where('sub_category_id',$service->sub_category_id)->get();

        $addons = Addon::all();

        $attribute = Attribute::all();

        $gallery = ServiceGallery::where('service_id',$service->id)->get();

        $attr_ids = [];
        $attr_val = ServiceAttributeValue::where('service_id',$service->id)->groupBy('attribute_id')->get();
        if($attr_val){

            foreach ($attr_val as $key => $atr_vl) {
                array_push($attr_ids, $atr_vl->attribute_id);
            }

        }

        $attr_items = ServiceAttributeValue::where('service_id',$service->id)->whereIn('attribute_id',$attr_ids)->get();
        
        return view('service.edit',compact('categorys','addons','attribute','service','attr_ids','attr_items','gallery','child_category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function delete_atr_item(Request $request)
    {
        $attr_items = ServiceAttributeValue::where('id',$request->atr_id)->delete();
        return $attr_items;
    }


    public function remove_service_attr(Request $request)
    {
        $attr_items = ServiceAttributeValue::where('service_id',$request->service_id)->where('attribute_id',$request->attribute_id)->delete();
        return $attr_items;
    }

    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, Service $service)
    {
        
        $this->validate($request,[
            'name'              => 'required',
            'video'             => 'nullable|mimes:mp4|max:51200',
            'video_info'        => 'nullable|string|max:255',
            'video_title'        => 'nullable|string|max:255',
            'video_description'        => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('video')) {
            if ($service->video && file_exists(public_path('uploads/service/' . $service->video))) {
                unlink(public_path('uploads/service/' . $service->video));
            }
    
            $videoName = time() . '.' . $request->video->extension();
            $request->video->move(public_path('uploads/service'), $videoName);
            $service->video = \URL::to('uploads/service/'.$videoName);
        }

        if ($request->video_title) {
            $service->video_title = $request->input('video_title');
        }

        if ($request->video_description) {
            $service->video_description = $request->input('video_description');
        }
        
        if ($request->info) {
            $service->info = $request->input('info');
        }

        if($request->hasFile('image')){

            $imageName = time().'.'.$request->image->extension(); 
            $path = $request->image->move(public_path('/uploads/service/'), $imageName);
            $params['thumbnail_img'] = $imageName;

        }

        if($request->hasFile('featured_banner')){

            $imageName = time().'.'.$request->featured_banner->extension(); 
            $path = $request->featured_banner->move(public_path('/uploads/service/featured_banner/'), $imageName);
            $params['featured_banner'] = $imageName;

        }
         
        $params['user_id']              = Auth::user()->id;

        $params['addon_id']             = $request->addon_id;

        $params['parent_id']            = $request->parent_id;

        $params['slug']                 = Str::slug($request->name);

        $params['name']                 = $request->name;

        $params['price']                = $request->price?$request->price:'0';

        $params['material_status']      = $request->material_status=='on'?'True':'False';

        $params['material_price']       = $request->material_price?$request->material_price:'0';

        $params['status']               = $request->status=='on'?'1':'0';

        $params['featured']             = $request->featured=='on'?'1':'0';

        $params['recommended']          = $request->recommended;

        $params['short_description']    = $request->short_description;

        $params['description']          = $request->description;

        $params['meta_title']           = $request->meta_title;

        $params['meta_description']     = $request->meta_description;

        $params['canonical']            = $request->canonical;

        $params['um_commission']        = $request->um_commission;

        $service->update($params);

        if($request->hasFile('gallery')){
            
            $imageName = time().'.'.$request->gallery->extension(); 
            $path = $request->gallery->move(public_path('/uploads/service/gallery/'), $imageName);
            $params_g['photos'] = $imageName;
            $params_g['service_id'] = $service->id;
            ServiceGallery::where('service_id',$service->id)->delete();
            ServiceGallery::create($params_g);   

        }

        if($service){

            return redirect('service')->with('success','Service updated successfully.');

        } else {

            return redirect('service')->with('error','Something want wrong.');

        }
    }

    public function deleteVideo($id)
    {
        $service = Service::findOrFail($id);
    
        $videoPath = parse_url($service->video, PHP_URL_PATH);
        $videoFilename = basename($videoPath); 
    
        $filePath = public_path('uploads/service/' . $videoFilename);
    
        if (file_exists($filePath)) {
            unlink($filePath); 
            $service->video = null;
            $service->save();
    
            return response()->json(['success' => true]);
        }
    
        return response()->json(['success' => false, 'message' => 'Video not found']);
    }      

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ServiceAttributeValue::where('service_id',$id)->delete();
        ServiceGallery::where('service_id',$id)->delete();
        Service::whereId($id)->delete();
        return redirect('service')->with('error','Service delete successfully.');
    }

    public function add_more_choice_option(Request $request)
    {

        $all_attribute_values = AttributeValue::where('attribute_id', $request->attribute_id)->get();
        $attribute_values = Attribute::find($request->attribute_id);

        $currencies = \DB::table('currencies')->where('default', '1')->first();

        $html = '';

        foreach ($all_attribute_values as $row) {

            $html .= '<div class="col-lg-5 form-group more' . $row->id . '">

                            <input type="text" value="'.$row->value.'" readonly class="form-control">
                            <input type="hidden" name="attribute_val_id_'.$request->attribute_id.'[]" value="' . $row->id . '" readonly class="form-control">

                        </div>

                        <div class="col-lg-5 form-group more' . $row->id . '">

                            <div class="input-group mb-3">

                                <div class="input-group-prepend">

                                    <span class="input-group-text" id="basic-addon1">'.$currencies->symbol.'</span>

                                </div>

                                    <input type="number" name="attribute_val_price_'.$request->attribute_id.'[]" class="form-control" placeholder="Price">

                            </div>                            

                        </div>
                        <div class="col-lg-2 form-group more' . $row->id . '">
                            <button type="button" class="btn btn-outline-danger" onclick="deleteMoreAtrItem(' . $row->id . ')"><i class="fa fa-times" aria-hidden="true"></i></button>
                        </div>
                        ';

        }
        $resp['html'] = $html;
        $resp['atrl'] = $attribute_values->name;
        echo json_encode($resp);
    }

    public function remove_gallery(Request $request)
    {
        $data = ServiceGallery::where('id',$request->id)->delete();
        return $data;
    }

    public function attributes(Request $request, $service_id='')
    {
        $data['service_atr'] = ServiceAttributeValueItem::where('service_id',$service_id)->paginate(10);
        $data['request'] = $request;
        $data['service_id'] = $service_id;
        return view('service.attribute.index',$data);
    }

    public function attribute_items(Request $request, $attribute_id='', $service_id)
    {
        $data['service_atr'] = ServiceAttributeValue::where('ser_attr_val_item_id',$attribute_id)->paginate(10);
        $data['request'] = $request;
        $data['attribute_id'] = $attribute_id;
        $data['service_id'] = $service_id;

        return view('service.attribute.item_list',$data);
    }

    public function create_attributes($service_id='')
    {
        $categorys = Category::where('status','1')->where('parent_id','0')->get();

        $service = Service::find($service_id);

        $attribute = Attribute::all();

        return view('service.attribute.create',compact('categorys','service','attribute'));
    }

    public function store_service_attribute(Request $request)
    {
        $this->validate($request,[
            'service_id'     => 'required',
            'parent_id'      => 'required',
            'attribute_id'   => 'required',
        ]);

        $params['service_id']           = $request->service_id;
        $params['category_id']          = $request->parent_id;
        $params['sub_category_id']      = $request->sub_category_id;
        $params['child_category_id']    = $request->child_category_id;
        
        $res = ServiceAttributeValueItem::create($params);

        if($request->attribute_id) {
            foreach ($request->attribute_id as $attr_id) {
                
                $req_p_id = 'attribute_val_id_'.$attr_id;
                $req_p_price = 'attribute_val_price_'.$attr_id;

                foreach ($request->$req_p_id as $key => $item_id) {

                    $attr_val_items['service_id']           = $request->service_id;
                    $attr_val_items['ser_attr_val_item_id'] = $res->id;
                    $attr_val_items['attribute_id']         = $attr_id;
                    $attr_val_items['attribute_item_id']    = $item_id;
                    $attr_val_items['attribute_price']      = $request->$req_p_price[$key]?$request->$req_p_price[$key]:'0';

                    ServiceAttributeValue::create($attr_val_items);

                }
            }
        }
        return redirect('service/attributes/'.$request->service_id)->with('success','Service Attribute create successfully.'); 
    }

    public function edit_attributes($id='')
    {
        $ser_atr = ServiceAttributeValueItem::find($id);

        $categorys = Category::where('status',1)->get();

        $child_category = ChildCategory::where('status','1')->where('sub_category_id',$ser_atr->sub_category_id)->get();

        $attribute = Attribute::all();

        $attr_ids = [];
        
        $attr_val = ServiceAttributeValue::where('ser_attr_val_item_id',$ser_atr->id)->groupBy('attribute_id')->get();        

        if($attr_val){

            foreach ($attr_val as $key => $atr_vl) {
                array_push($attr_ids, $atr_vl->attribute_id);
            }

        }
        
       $attr_items = ServiceAttributeValue::where('ser_attr_val_item_id',$ser_atr->id)->get();
        
        return view('service.attribute.edit',compact('categorys','attribute','ser_atr','attr_ids','attr_items','child_category'));
    }

    public function update_attributes(Request $request, $id='')
    {
        $this->validate($request,[
            'service_id'     => 'required',
            'parent_id'      => 'required',
            // 'attribute_id'   => 'required',
        ]);
  
        $data = ServiceAttributeValueItem::find($id);

        $params['service_id']           = $request->service_id;
        $params['category_id']          = $request->parent_id;
        $params['sub_category_id']      = $request->sub_category_id;
        $params['child_category_id']    = $request->child_category_id;

        $data->update($params);

        if($request->remove_atr_id){
            ServiceAttributeValue::where('attribute_id',$request->remove_atr_id)->where('service_id',$request->service_id)->where('ser_attr_val_item_id',$data->id)->delete();
        }

        if($request->attribute_id) {
            ServiceAttributeValue::where('ser_attr_val_item_id',$data->id)->delete();
            foreach ($request->attribute_id as $attr_id) {
                
                $req_p_id = 'attribute_val_id_'.$attr_id;
                $req_p_price = 'attribute_val_price_'.$attr_id;

                foreach ($request->$req_p_id as $key => $item_id) {

                    $attr_val_items['service_id']           = $request->service_id;
                    $attr_val_items['ser_attr_val_item_id'] = $data->id;
                    $attr_val_items['attribute_id']         = $attr_id;
                    $attr_val_items['attribute_item_id']    = $item_id;
                    $attr_val_items['attribute_price']      = $request->$req_p_price[$key]?$request->$req_p_price[$key]:'0';

                    ServiceAttributeValue::create($attr_val_items);

                }
            }
        }
        return redirect('service/attributes/'.$request->service_id)->with('success','Service Attribute update successfully.'); 
    }

    public function delete_attributes($id='')
    {
        $data = ServiceAttributeValueItem::find($id);
        ServiceAttributeValue::where('ser_attr_val_item_id',$data->id)->delete();
        $data->delete();
        return redirect()->back()->with('error','Service Attribute delete successfully.');
    }

    public function coupons()
    {
        $data['coupons'] = Coupon::orderBy('id','DESC')->get();
        return view('coupon.index',$data);
    }

    public function create_coupon()
    {
        return view('coupon.create');
    }

    public function store_coupon(Request $request)
    {
        $this->validate($request,[
            'code'          => 'required|unique:coupons,code',
            'type'          => 'required',
            'amount'        => 'required',
            'min_amount'    => 'required',
            'start_date'    => 'required',
            'end_date'      => 'required'
        ]);
      
        $data['code']       = $request->code;
        $data['user_used']  = $request->user_used;
        $data['type']       = $request->type;
        $data['amount']     = $request->amount;
        $data['min_amount'] = $request->min_amount;
        $data['max_amount'] = $request->max_amount;
        $data['start_date'] = $request->start_date;
        $data['end_date']   = $request->end_date;
        Coupon::create($data);
        return redirect('coupons')->with('success','Coupon create successfully.'); 
    }

    public function edit_coupon($id)
    {
        $data['coupon'] = Coupon::find($id);
        return view('coupon.edit',$data);
    }

    public function change_coupon($id='', $status)
    {
        $data['status'] = $status;
        Coupon::where('id',$id)->update($data);
        return redirect('coupons')->with('success','Status change successfully.'); 
    }

    public function update_coupon(Request $request)
    {
        $this->validate($request,[
            'code'          => 'required|unique:coupons,code' . $request->id,
            'type'          => 'required',
            'amount'        => 'required',
            'min_amount'    => 'required',
            'start_date'    => 'required',
            'end_date'      => 'required'
        ]);

        $data['code']       = $request->code;
        $data['type']       = $request->type;
        $data['amount']     = $request->amount;
        $data['min_amount'] = $request->min_amount;
        $data['max_amount'] = $request->max_amount;
        $data['start_date'] = $request->start_date;
        $data['end_date']   = $request->end_date;
        Coupon::where('id',$request->id)->update($data);
        return redirect('coupons')->with('success','Coupon update successfully.'); 
    }

    public function delete_coupon($id)
    {
        $coupon = Coupon::find($id);
        $coupon->delete();
        return redirect('coupons')->with('error','Coupon delete successfully.');
    }
}
