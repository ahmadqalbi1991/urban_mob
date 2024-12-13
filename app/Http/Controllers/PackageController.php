<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Package;
use App\PackageItem;
use App\PackageAddons;
use App\PackageAddonItems;
use App\PackageLeave;

class PackageController extends Controller
{
    public function index()
    {   
        $packages = Package::with('customer','vendor')->orderBy('id','DESC')->paginate(10);
        //dd($packages);
        return view('packages', compact(['packages']));
    }

    public function package_detail($id)
    {   
        $id=decrypt($id);
        $package = Package::where('id',$id)->with('items','customer','vendor','leave')->first();
        //dd($package);
        if($package){
            return view('package_detail',compact('package'));
        }
        else{
            return redirect()->route('home')
                        ->with('warning','Something is wrong, Try Later!');
        }
    }

    public function addons()
    {   
        $addons = PackageAddons::with('customer','vendor')->paginate(10);
        //dd($packages);
        return view('package_addons', compact(['addons']));
    }

    public function addon_detail($id)
    {
        $id=decrypt($id);
        $addon = PackageAddons::where('id',$id)->with('items','customer','vendor')->first();
        //dd($package);
        if($addon){
            return view('package_addon_detail',compact('addon'));
        }
        else{
            return redirect()->route('home')
                        ->with('warning','Something is wrong, Try Later!');
        }
    }

    public function package_leave()
    {   
        $list = PackageLeave::with('package')->orderBy('leave_date','DESC')->paginate(10);
        //dd($list);
        return view('package_leaves', compact(['list']));
    }

}
