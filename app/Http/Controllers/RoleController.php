<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Validator;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        if($request){
            $data['roles'] = Role::where('name', 'like', '%' . $request->search . '%')->paginate(10);
        } else {
            $data['roles'] = Role::paginate(10);
        }
       
        $data['request'] = $request;
        return view('roles.roles',$data);
    }

    public function create()
    {
        $data['permissions']    =   Permission::all();

        $role_permission = Permission::select('name','id')->groupBy('name')->get();

        $custom_permission = array();

        foreach($role_permission as $per){

            $key = substr($per->name, 0, strpos($per->name, ".")); 

            if(str_starts_with($per->name, $key)){
                $custom_permission[$key][] = $per;
            }
            
        }
        // return $custom_permission;
        $data['custom_permission'] = $custom_permission;
        return view('roles.create_role',$data);
    }

    public function store(Request $request)
    {
        // $request->validate([
        //     'role' => 'required|role|unique:roles',
        // ]);

        if(Role::where('name', $request->role)->count()>0){
            return redirect()->route('manage.role')->with('error','This role already excited.');
        }
        
       $role = Role::create(['name' => $request->role]);

        if($request->permissions){
            foreach ($request->permissions as $key => $value) {
                $role->givePermissionTo($value);
            }
        }
        return redirect()->route('manage.role')->with('success','Role created successfully.');
    }

    public function update(Request $request,$id)
    {
        $request->validate([
            'name' => 'required|unique:roles',
        ]);

        $params['name'] = $request->name;
        Role::whereId($id)->update($params);
        return redirect()->route('manage.role')->with('success','Role update successfully.');
    }

    public function delete($role_id='')
    {
        $roleid=decrypt($role_id);
        $role = Role::find($roleid);
        $role->permissions()->detach();
        $role->delete();
        return back()->with('error','Role deleted successfully.');
    }

    public function permission($roleid='')
    {
        $data['role_id']        =   $roleid;
        // $data['role_id']        =   decrypt($roleid);
        $data['roles']          =   Role::all();
        $data['permissions']    =   Permission::all();
        $permissionIds = [];
        $dats = Role::where('roles.id', $data['role_id'])
                ->with('permissions')
                ->first();
               
        if($dats['permissions']){
            foreach ($dats['permissions'] as $key => $value) {
                array_push($permissionIds, $value->id);
            }
        }
         // return $permissionIds;
        $data['permissionIds'] = $permissionIds;

        $role_permission = Permission::select('name','id')->groupBy('name')->get();

        $custom_permission = array();

        foreach($role_permission as $per){

            $key = substr($per->name, 0, strpos($per->name, ".")); 

            if(str_starts_with($per->name, $key)){
                $custom_permission[$key][] = $per;
            }
            
        }
        // return $custom_permission;
        $data['custom_permission'] = $custom_permission;
        return view('roles.permission',$data);
    }


    public function permission_filter($roleid='')
    {
        $data['role_id']        =   $roleid;
        $data['roles']          =   Role::all();
        $data['permissions']    =   Permission::all();
        $permissionIds = [];
        $dats = Role::where('roles.id', $data['role_id'])
                ->with('permissions')
                ->first();
        if($dats['permissions']){
            foreach ($dats['permissions'] as $key => $value) {
                array_push($permissionIds, $value->id);
            }
        }
        
        $data['permissionIds'] = $permissionIds;

        $html="";
        if(!empty($data['permissions']))
        {
            foreach ($data['permissions'] as $key => $permission) {
                if(in_array($permission->id, $permissionIds)){
                $html.='                                        
                    <div class="col-lg-4">
                        <label class="ml-2">
                            
                               <input name="permissions[]" class="permissioncheckbox" type="checkbox" value="'.$permission->id.'" checked> 
                               
                           &nbsp;&nbsp;'.$permission->name.' &nbsp;&nbsp;
                       </label>
                    </div>';
                    } else {
                    $html.='                                        
                    <div class="col-lg-4">
                        <label class="ml-2">
                            <input name="permissions[]" class="permissioncheckbox" type="checkbox" value="'. $permission->id .'">
                           &nbsp;&nbsp;'.$permission->name.' &nbsp;&nbsp;
                       </label>
                    </div>';
                    }
            }
        }
        else
        {
               $html='';
        }
        return response()->json($html);
        // return view('roles.permission',$data);
    }

    public function update_assign_permission(Request $request)
    {
        $request->validate([
            'role_id' => 'required',
        ]);

        $role = Role::find($request->role_id);

        $role->save();

        $role->syncPermissions($request->permissions);

        return redirect()->route('manage.role')->with('success','Role update successfully.');
    }
    
}
