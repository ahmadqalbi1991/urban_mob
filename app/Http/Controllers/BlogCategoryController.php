<?php

namespace App\Http\Controllers;

use App\BlogCategory;
use Illuminate\Http\Request;

class BlogCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->search){
            $blogcategory = BlogCategory::orderBy('id','DESC')->where('name', 'like', '%'.$request->search.'%')->paginate(10);
        } else {
            $blogcategory = BlogCategory::orderBy('id','DESC')->paginate(10);
        }
        
        return view('blogcategory.index',compact('blogcategory','request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('blogcategory.create');
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

        if($request->hasFile('logo')){
            $imageName = $request->name.'-'.time().'.'.$request->logo->extension(); 
            $path = $request->logo->move(public_path('/uploads/blog-category/'), $imageName);
            $data['logo'] = $imageName;
        }
        
        $data['name'] = $request->name;
        BlogCategory::create($data);
        return redirect('blog-category')->with('success','Blog category created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\BlogCategory  $blogCategory
     * @return \Illuminate\Http\Response
     */
    public function show(BlogCategory $blogCategory)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\BlogCategory  $blogCategory
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id=decrypt($id);
        $blogcategory = BlogCategory::find($id);
        return view('blogcategory.edit',compact('blogcategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BlogCategory  $blogCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if($request->hasFile('logo')){
            $imageName = $request->name.'-'.time().'.'.$request->logo->extension(); 
            $path = $request->logo->move(public_path('/uploads/blog-category/'), $imageName);
            $data['logo'] = $imageName;
        }
        
        $data['name'] = $request->name;
        BlogCategory::whereId($id)->update($data);
        
        return redirect('blog-category')->with('success','Blog category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BlogCategory  $blogCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id=decrypt($id);
        BlogCategory::whereId($id)->delete();
        
        return redirect()->back()->with('error','Blog category deleted successfully.');
    }
}
