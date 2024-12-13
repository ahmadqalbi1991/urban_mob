<?php

namespace App\Http\Controllers;

use Auth;
use App\Blog;
use App\BlogCategory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->search){
            $blogcategory = Blog::orderBy('id','DESC')->where('name', 'like', '%'.$request->search.'%')->paginate(10);
        } else {
            $blog = Blog::orderBy('id','DESC')->get();
        }
        
        return view('blog.index',compact('blog','request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $blogcategory = BlogCategory::where('status','1')->get();
        return view('blog.create',compact('blogcategory'));
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
            'category_id' => 'required',
            'name' => 'required',
            'details' => 'required',
        ]);

        if($request->hasFile('image')){
            $imageName = $request->name.'-'.time().'.'.$request->image->extension(); 
            $path = $request->image->move(public_path('/uploads/blog/'), $imageName);
            $data['image'] = $imageName;
        }

        if($request->hasFile('banner')){
            $bannerName = $request->name.'-'.time().'.'.$request->banner->extension(); 
            $path = $request->banner->move(public_path('/uploads/blog/'), $bannerName);
            $data['banner'] = $bannerName;
        }
        
        $data['user_id']        = Auth::user()->id;
        $data['name']           = $request->name;
        $data['slug']           = Str::slug($request->name);
        $data['category_id']    = $request->category_id;
        $data['details']        = $request->details;
        $data['meta_title']        = $request->meta_title;
        $data['meta_keyword']        = $request->meta_keyword;
        $data['meta_description']        = $request->meta_description;
        Blog::create($data);
        return redirect('blog')->with('success','Blog created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function show(Blog $blog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id=decrypt($id);
        $blog = Blog::find($id);
        $blogcategory = BlogCategory::where('status','1')->get();
        return view('blog.edit',compact('blog','blogcategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'category_id' => 'required',
            'name' => 'required',
            'details' => 'required',
        ]);
        
        if($request->hasFile('image')){
            $imageName = time().'.'.$request->image->extension(); 
            $path = $request->image->move(public_path('/uploads/blog/'), $imageName);
            $data['image'] = $imageName;
        }

        if($request->hasFile('banner')){
            $bannerName = time().'.'.$request->banner->extension(); 
            $path = $request->banner->move(public_path('/uploads/blog/'), $bannerName);
            $data['banner'] = $bannerName;
        }
        
        $data['slug']           = Str::slug($request->name);
        $data['name']           = $request->name;
        $data['category_id']    = $request->category_id;
        $data['details']        = $request->details;
        $data['status']         = $request->status;
        $data['meta_title']        = $request->meta_title;
        $data['meta_keyword']        = $request->meta_keyword;
        $data['meta_description']        = $request->meta_description;
        Blog::whereId($id)->update($data);
        
        return redirect('blog')->with('success','Blog updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id=decrypt($id);
        Blog::whereId($id)->delete();
        
        return redirect()->back()->with('error','Blog deleted successfully.');
    }
}
