<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ChildCategory;
use App\Category;

class ChildCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->search){
            $category = ChildCategory::orderBy('id','DESC')->where('name', 'like', '%'.$request->search.'%')->paginate(10);
        } else {
            $category = ChildCategory::orderBy('id','DESC')->get();
        }

        return view('catgeory.child_category.index',compact('category','request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categorys = Category::where('status',1)->where('parent_id','!=',0)->get();
       return view('catgeory.child_category.create',compact('categorys'));
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
            'price' => 'required',
        ]);

        if($request->hasFile('icon')){
            $imageName = time().'.'.$request->icon->extension(); 
            $path = $request->icon->move(public_path('/uploads/child-category/'), $imageName);
            $data['icon'] = $imageName;
        }
        
        $data['name'] = $request->name;
        $data['price'] = $request->price;
        $data['sub_category_id'] = $request->parent_id?$request->parent_id:'0';
        $data['status'] = $request->status=='on'?'1':'0';
        $data = ChildCategory::create($data);
        if($data){
            return redirect('child-category')->with('success','Child category created successfully.');
        } else {
            return redirect('child-category')->with('error','Something want wrong.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $categorys = Category::where('status',1)->where('parent_id','!=',0)->get();
        $category = ChildCategory::find($id);
        return view('catgeory.child_category.edit',compact('category','categorys'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'name' => 'required',
            'price' => 'required',
        ]);
        
        if($request->hasFile('icon')){
            $imageName = time().'.'.$request->icon->extension(); 
            $path = $request->icon->move(public_path('/uploads/child-category/'), $imageName);
            $data['icon'] = $imageName;
        }
        $data['name'] = $request->name;
        $data['price'] = $request->price;
        $data['sub_category_id'] = $request->parent_id;
        $data['status'] = $request->status=='on'?'1':'0';
        ChildCategory::whereId($id)->update($data);
        return redirect('child-category')->with('success','Child category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id=decrypt($id);
        ChildCategory::whereId($id)->delete();
        return redirect()->back()->with('error','Child category deleted successfully.');
    }
}
