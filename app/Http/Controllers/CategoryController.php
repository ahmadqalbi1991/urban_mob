<?php

namespace App\Http\Controllers;

use Image;
use App\Category;
use App\ChildCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->search){
            $category = Category::orderBy('id','DESC')->where('parent_id',0)->where('name', 'like', '%'.$request->search.'%')->paginate(10);
        } else {
            $category = Category::orderBy('id','DESC')->where('parent_id',0)->get();
        }
        
        return view('catgeory.index',compact('category','request'));
    }

    public function sub_index(Request $request)
    {
        if($request->search){
            $category = Category::orderBy('id','DESC')->where('parent_id', '!=' ,0)->where('name', 'like', '%'.$request->search.'%')->paginate(10);
        } else {
            $category = Category::orderBy('id','DESC')->where('parent_id', '!=' ,0)->orderBy('id', 'DESC')->get();
        }
        
        return view('catgeory.sub_index',compact('category','request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['categorys'] = Category::where('status',1)->where('parent_id',0)->get();
        $data['title'] = 'Category';
        return view('catgeory.create',$data);
    }

    public function subCatCreate()
    {
        $data['categorys'] = Category::where('status',1)->where('parent_id',0)->get();
        $data['title'] = 'Sub Category';
        return view('catgeory.create',$data);
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
            'meta_description' => 'max:100',
        ]);

        if($request->hasFile('icon')){
            $imageName = time().'.'.$request->icon->extension(); 
            $path = $request->icon->move(public_path('/uploads/category/'), $imageName);
            $data['icon'] = $imageName;
        }
        
        $data['name'] = $request->name;
        $data['price'] = $request->price;
        $data['parent_id'] = $request->parent_id?$request->parent_id:'0';
        $data['meta_title'] = $request->meta_title;
        $data['meta_description'] = $request->meta_description;
        $data['status'] = $request->status=='on'?'1':'0';
        $data = Category::create($data);
        if($request->parent_id){
            if($data){
                return redirect('sub-category')->with('success','Sub Category created successfully.');
            } else {
                return redirect('sub-category')->with('error','Something want wrong.');
            }
        } else {
            if($data){
                return redirect('category')->with('success','Category created successfully.');
            } else {
                return redirect('category')->with('error','Something want wrong.');
            }
        }       
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id=decrypt($id);
        $categorys = Category::where('status',1)->where('parent_id',0)->get();
        $category = Category::find($id);
        return view('catgeory.edit',compact('category','categorys'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'name' => 'required',
            'price' => 'required',
            'meta_description' => 'max:100',
        ]);
       
        if($request->hasFile('icon')){
            $imageName = time().'.'.$request->icon->extension(); 
            $path = $request->icon->move(public_path('/uploads/category/'), $imageName);
            $data['icon'] = $imageName;
        }
        $data['name'] = $request->name;
        $data['price'] = $request->price;
        $data['parent_id'] = $request->parent_id;
        $data['meta_title'] = $request->meta_title;
        $data['meta_description'] = $request->meta_description;
        $data['status'] = $request->status=='on'?'1':'0';

        Category::whereId($id)->update($data);
        if($request->parent_id){
            if($data){
                return redirect('sub-category')->with('success','Sub Category created successfully.');
            } else {
                return redirect('sub-category')->with('error','Something want wrong.');
            }
        } else {
            if($data){
                return redirect('category')->with('success','Category created successfully.');
            } else {
                return redirect('category')->with('error','Something want wrong.');
            }
        }      
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $id=decrypt($id);
        Category::whereId($id)->delete();
        return redirect()->back()->with('error','Category deleted successfully.');
    }

    public function status($cat_id,$status)
    {
        $params['status'] = $status;
        $category = Category::whereId($cat_id)->update($params);
        if($category){
            return redirect()->back()->with('success','Category status changed successfully.');
        }
        else{
            return redirect()->back()->with('error','Something is wrong, Try Later.');
        }
    }

    public function get_sub_category(Request $request)
    {
        $category = Category::where('status','1')->where('parent_id',$request->category_id)->get();
        $html = '<option value="">Select Sub Category</option>';
        foreach ($category as $row) {

            $html .= '<option value="' . $row->id . '">' . $row->name . '</option>';

        }
        echo json_encode($html);
    }

    public function get_child_category(Request $request)
    {
        $category = ChildCategory::where('status','1')->where('sub_category_id',$request->category_id)->get();
        $html = '<option value="">Select Child Category</option>';
        foreach ($category as $row) {

            $html .= '<option value="' . $row->id . '">' . $row->name . '</option>';

        }
        echo json_encode($html);
    }
}
