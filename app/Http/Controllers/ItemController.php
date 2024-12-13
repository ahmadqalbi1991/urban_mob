<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Item;

class ItemController extends Controller
{
    public function index()
    {   
        $items = Item::all();
        return view('items', compact('items'));
    }
    public function item_add(Request $request)
    {
        
        $request->validate([
            'name' => 'required|min:2',
            'brand' => 'required',
        ]);
        $fileName='';
        if(isset($request->icon) && !empty($request->icon))
        {   
            $request->validate([
                'icon' => 'required|mimes:jpg,png,jpeg,gif|max:2048',
            ]);
            $fileName = time().'.'.$request->icon->extension();  
            $request->icon->move(public_path('uploads/items'), $fileName);
        }
        
        Item::create([
            'name' => $request->name,
            'brand' => $request->brand,
            'icon' => $fileName,
        ]);
   
        return redirect()->route('items')
                        ->with('success','Item created successfully.');
    }
    public function item_update(Request $request, Item $item)
    {
        
        $request->validate([
            'name' => 'required|min:2',
            'brand' => 'required',
        ]);
        
        if(isset($request->icon) && !empty($request->icon))
        {   
            $request->validate([
                'icon' => 'required|mimes:jpg,png,jpeg,gif|max:2048',
            ]);
            $fileName = time().'.'.$request->icon->extension();  
            $request->icon->move(public_path('uploads/items'), $fileName);
        }
        else {
            $fileName=$request->icon_image;
        }
        
  
        $item->update([
            'name' => $request->name,
            'brand' => $request->brand,
            'icon' => $fileName,
        ]);
   
        return redirect()->route('items')
                        ->with('success','Item updated successfully.');
    }
    public function item_status($item_id,$status)
    {
        $item = Item::find($item_id);
        $item->is_active = $status;
        $res=$item->save();
        if($res){
            return redirect()->route('items')
                        ->with('success','Item status changed successfully.');
        }
        else{
            return redirect()->route('items')
                        ->with('error','Something is wrong, Try Later.');
        }
    }
}
