<?php

namespace App\Http\Controllers;

use App\Slot;
use Illuminate\Http\Request;

class SlotController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->search){
            $slot = Slot::orderBy('id','DESC')->where('name', 'like', '%'.$request->search.'%')->paginate(10);
        } else {
            $slot = Slot::orderBy('id','DESC')->get();
        }
        return view('slot.index',compact('slot','request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         return view('slot.create');
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

        $data['name']       = $request->name;
        $data['check_in']   = $request->check_in;
        $data['check_out']  = $request->check_out;

        $data = Slot::create($data);

        if($data){
            return redirect('slots')->with('success','Slot created successfully.');
        } else {
            return redirect('slots')->with('error','Something want wrong.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Slot  $slot
     * @return \Illuminate\Http\Response
     */
    public function show(Slot $slot)
    {
        return view('slot.edit',compact('slot'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Slot  $slot
     * @return \Illuminate\Http\Response
     */
    public function edit(Slot $slot)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Slot  $slot
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Slot $slot)
    {
        $this->validate($request,[
            'name' => 'required',
        ]);

        $data['name']       = $request->name;
        $data['check_in']   = $request->check_in;
        $data['check_out']  = $request->check_out;
   
        $data = $slot->update($data);

        if($data){
            return redirect('slots')->with('success','Slot updated successfully.');
        } else {
            return redirect('slots')->with('error','Something want wrong.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Slot  $slot
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id=decrypt($id);
        Slot::whereId($id)->delete();
        return redirect()->back()->with('error','Slot deleted successfully.');
    }
}
