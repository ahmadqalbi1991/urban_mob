<?php

namespace App\Http\Controllers;

use App\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        if($request->search){
            $brand = Brand::orderBy('id','DESC')->where('name', 'like', '%'.$request->search.'%')->paginate(10);
        } else {
            $brand = Brand::orderBy('id','DESC')->paginate(10);
        }
        
        return view('brand.index',compact('brand','request'));
    }

    public function create(Request $request)
    {
        return view('brand.create');
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required',
        ]);

        if($request->hasFile('logo')){
            $imageName = $request->name.'-'.time().'.'.$request->logo->extension(); 
            $path = $request->logo->move(public_path('/uploads/brand/'), $imageName);
            $data['logo'] = $imageName;
        }
        
        $data['name'] = $request->name;
        Brand::create($data);
        return redirect('brand')->with('success','Brand created successfully.');
    }

    public function edit($id)
    {
        $id=decrypt($id);
        $brand = Brand::find($id);
        return view('brand.edit',compact('brand'));
    }

    public function update(Request $request, $id)
    {
        if($request->hasFile('logo')){
            $imageName = $request->name.'-'.time().'.'.$request->logo->extension(); 
            $path = $request->logo->move(public_path('/uploads/brand/'), $imageName);
            $data['logo'] = $imageName;
        }
        $data['name'] = $request->name;
        Brand::whereId($id)->update($data);
        
        return redirect('brand')->with('success','Brand updated successfully.');
    }

    public function delete($id)
    {
        $id=decrypt($id);
        Brand::whereId($id)->delete();
        
        return redirect()->back()->with('error','Brand deleted successfully.');
    }
}
