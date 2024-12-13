<?php

namespace App\Http\Controllers;

use App\Addon;
use App\Category;
use App\ChildCategory;
use App\AttributeValue;
use App\ServiceAttributeValue;
use App\ServiceAttributeValueItem;
use Illuminate\Http\Request;

class AddonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function attribute_addon(Request $request, $attribute, $service, $atr_item)
    {
        if($request->search){
            $addon = Addon::orderBy('id','DESC')->where('attribute_item_id',$atr_item)->where('name', 'like', '%'.$request->search.'%')->paginate(10);
        } else {
            $addon = Addon::orderBy('id','DESC')->where('attribute_item_id',$atr_item)->paginate(10);
        }

        return view('addon.index',compact('addon','request','attribute','service','atr_item'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($attribute_id, $service_id, $attr_item)
    {  
        $category = Category::where('status','1')->where('parent_id','0')->get();
        $cat_name = AttributeValue::find($attr_item);
       
        return view('addon.create',compact('attribute_id','service_id','attr_item','cat_name','category'));
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
            'name' => 'required',
            'attribute_item_id' => 'required',
            'value' => 'required',
            // 'icon' => 'required',
        ]);

        if($request->hasFile('icon')){
            $imageName = $request->name.'-'.time().'.'.$request->icon->extension(); 
            $path = $request->icon->move(public_path('/uploads/addon/'), $imageName);
            $data['icon'] = $imageName;
        }
        
        $data['service_id']             = $request->service_id;
        $data['category_id']            = $request->category_id;
        $data['sub_category_id']        = $request->sub_category_id;
        $data['child_category_id']      = $request->child_category_id;
        $data['ser_attr_val_item_id']   = $request->ser_attr_val_item_id;
        $data['attribute_id']           = $request->attribute_id;
        $data['attribute_item_id']      = $request->attribute_item_id;
        $data['percentage']             = $request->percentage;
        $data['orignal_price']          = $request->orignal_price;
        $data['name']                   = $request->name;
        $data['value']                  = $request->value;
        $data['short_description']      = $request->short_description;
        Addon::create($data);
        return redirect('manage/service/attribute/addon/'.$request->attribute_id.'/'.$request->service_id.'/'.$request->attribute_item_id)->with('success','Addon created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Addon  $addon
     * @return \Illuminate\Http\Response
     */
    public function show(Addon $addon)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Addon  $addon
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id=decrypt($id);
        $addon = Addon::find($id);

        if($addon->child_category_id){
            $data = ServiceAttributeValueItem::where('category_id',$addon->category_id)->where('child_category_id',$addon->child_category_id)->get();
        } elseif ($addon->sub_category_id) {
            $data = ServiceAttributeValueItem::where('category_id',$addon->category_id)->where('sub_category_id',$addon->sub_category_id)->get();
        } else {
            $data = ServiceAttributeValueItem::where('category_id',$addon->category_id)->get();
        }
        
        $ids = [];
        foreach ($data as $key => $value) {
            array_push($ids, $value->id);
        }
        
        $datas = ServiceAttributeValue::whereIn('ser_attr_val_item_id',$ids)->get();
        
        $category = Category::where('status','1')->where('parent_id','0')->get();
        $sub_category = Category::where('status','1')->where('parent_id',$addon->category_id)->get();
        $child_category = ChildCategory::where('sub_category_id',$addon->sub_category_id)->get();
        return view('addon.edit',compact('addon','datas','category','sub_category','child_category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Addon  $addon
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'name' => 'required',
            'value' => 'required',
            // 'icon' => 'required',
        ]);
        
        if($request->hasFile('icon')){
            $imageName = $request->name.'-'.time().'.'.$request->icon->extension(); 
            $path = $request->icon->move(public_path('/uploads/addon/'), $imageName);
            $data['icon'] = $imageName;
        }

        $data['category_id']            = $request->category_id;
        $data['sub_category_id']        = $request->sub_category_id;
        $data['child_category_id']      = $request->child_category_id;
        $data['attribute_id']           = $request->attribute_id;
        $data['attribute_item_id']      = $request->attribute_item_id;
        $data['percentage']             = $request->percentage;
        $data['orignal_price']          = $request->orignal_price;
        $data['name']                   = $request->name;
        $data['value']                  = $request->value;
        $data['short_description']      = $request->short_description;
        Addon::whereId($id)->update($data);
        $addon = Addon::find($id);
        return redirect('manage/service/attribute/addon/'.$addon->attribute_id.'/'.$addon->service_id.'/'.$addon->attribute_item_id)->with('success','Addon updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Addon  $addon
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id=decrypt($id);
        Addon::whereId($id)->delete();
        
        return redirect()->back()->with('error','Addon deleted successfully.');
    }

    public function service_atr(Request $request)
    {
        $data = ServiceAttributeValue::find($request->id);
        return $data;
    }

    public function cat_atr(Request $request)
    {
        if($request->posi=='main'){
            $data = ServiceAttributeValueItem::where('category_id',$request->pat_id)->get();
        } elseif ($request->posi=='sub') {
            $data = ServiceAttributeValueItem::where('category_id',$request->pat_id)->where('sub_category_id',$request->cat_id)->get();
        } elseif ($request->posi=='child'){
            $data = ServiceAttributeValueItem::where('category_id',$request->pat_id)->where('child_category_id',$request->cat_id)->get();
        }
        
        $ids = [];
        foreach ($data as $key => $value) {
            array_push($ids, $value->id);
        }

        $data_info = ServiceAttributeValue::whereIn('ser_attr_val_item_id',$ids)->get();

        $html = '<option value="">Select Attribute Item</option>';
        foreach ($data_info as $row) {

            $html .= '<option value="' . $row->id . '">' . $row->attributeItem->value . '</option>';

        }
        echo $html;
    }
}
