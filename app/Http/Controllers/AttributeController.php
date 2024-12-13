<?php

namespace App\Http\Controllers;

use App\Attribute;
use App\AttributeValue;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->search){
            $attribute = Attribute::orderBy('id','DESC')->where('name', 'like', '%'.$request->search.'%')->paginate(10);
        } else {
            $attribute = Attribute::orderBy('id','DESC')->get();
        }
        
        return view('attribute.index',compact('attribute','request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function manage(Request $request, $aid)
    {
        $id=decrypt($aid);
        $attribute_info = Attribute::find($id);
         if($request->search){
            $attribute = AttributeValue::orderBy('id','DESC')->where('attribute_id',$id)->where('value', 'like', '%'.$request->search.'%')->paginate(10);
        } else {
            $attribute = AttributeValue::orderBy('id','DESC')->where('attribute_id',$id)->get();
        }
        
        return view('attribute.manage.index',compact('attribute','request','attribute_info'));
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
        ]);        

        $data['name'] = $request->name;
        Attribute::create($data);
        return redirect()->back()->with('success','Attribute created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Attribute  $attribute
     * @return \Illuminate\Http\Response
     */
    public function attributevalue_store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required',
        ]);

        if($request->hasFile('icon')){
            $imageName = $request->name.'-'.time().'.'.$request->icon->extension(); 
            $path = $request->icon->move(public_path('/uploads/attribute/'), $imageName);
            $data['icon'] = $imageName;
        }

        $data['value'] = $request->name;
        $data['attribute_id'] = $request->attribute_id;
        AttributeValue::create($data);
        return redirect()->back()->with('success','Attribute value created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Attribute  $attribute
     * @return \Illuminate\Http\Response
     */
    public function attributevalue_update(Request $request, $id)
    {
        $this->validate($request,[
            'name' => 'required',
        ]);

        if($request->hasFile('icon')){
            $imageName = $request->name.'-'.time().'.'.$request->icon->extension(); 
            $path = $request->icon->move(public_path('/uploads/attribute/'), $imageName);
            $data['icon'] = $imageName;
        }

        $data['value'] = $request->name;
        AttributeValue::whereId($id)->update($data);
        return redirect()->back()->with('success','Attribute value updated successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Attribute  $attribute
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'name' => 'required',
        ]);

        $data['name'] = $request->name;
        Attribute::whereId($id)->update($data);
        return redirect()->back()->with('success','Attribute updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Attribute  $attribute
     * @return \Illuminate\Http\Response
     */
    public function status($aid,$status)
    {
        $params['status'] = $status;
        $Attribute = Attribute::whereId($aid)->update($params);
        if($Attribute){
            return redirect()->back()->with('success','Category status changed successfully.');
        }
        else{
            return redirect()->back()->with('error','Something is wrong, Try Later.');
        }
    }

    public function delete($id)
    {
        $id=decrypt($id);
        Attribute::whereId($id)->delete();
        AttributeValue::where('attribute_id',$id)->delete();
        return redirect()->back()->with('error','Attribute deleted successfully.');
    }

    public function attributevalue_delete($id)
    {
        $id=decrypt($id);
        AttributeValue::whereId($id)->delete();
        
        return redirect()->back()->with('error','Attribute value deleted successfully.');
    }
}
