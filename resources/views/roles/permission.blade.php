@extends('layouts.dashboard')

@section('content')

        <!--**********************************

            Content body start

        ***********************************-->

        <div class="content-body">

            <div class="row page-titles mx-0">

                    <div class="col-sm-6 p-md-0">

                        <div class="breadcrumb-range-picker">

                            <h3 class="ml-1">Assign Permission</h3>

                        </div>

                    </div>

                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">

                        <a href="{{ route('manage.role') }}"><button type="button" class="btn btn-rounded bg-grad-4 ml-4"><span class="btn-icon-left text-primary"><i class="fa fa-arrow-left color-primary"></i> </span>Back</button></a>
                        
                    </div>

                </div>

            <div class="container-fluid">

                @include('flash_msg')

                <div class="row">

                    <div class="">

                        <div class="card">

                            <div class="card-body">

                                <form action="{{ route('assign.permission') }}" method="POST" id="vendor_register">
                                    @csrf

                                    <div class="form-group row">
                                        <div class="col-lg-6">
                                            <label>Role<span class="text-danger">*</span></label>

                                            <select class="form-control select2" name="role_id" required onchange="changeRole(this.value)">
                                                <option value="">Select Role</option>
                                                @foreach($roles as $role)
                                                <option value="{{$role->id}}" {{$role_id==$role->id ? 'selected': ''}}>{{$role->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mt-4 ">
                                        <!-- @foreach($permissions as $key => $permission)
                                        <div class="col-lg-4">
                                            <label class="ml-2">
                                                @if(in_array($permission->id, $permissionIds))
                                                <input name="permissions[]" class="permissioncheckbox" type="checkbox" value="{{ $permission->id }}" checked>
                                                @else 
                                                <input name="permissions[]" class="permissioncheckbox" type="checkbox" value="{{ $permission->id }}">
                                                @endif
                                               
                                               &nbsp;&nbsp;{{$permission->name}} &nbsp;&nbsp;
                                           </label>
                                        </div>
                                        @endforeach -->
                                        <table class="permissionTable table">
                                            <th>
                                                {{__('Section')}}
                                            </th>
                                
                                            <th>
                                                <label>
                                                    <input class="grand_selectall" type="checkbox">
                                                    {{__('Select All') }}
                                                </label>
                                            </th>
                                
                                            <th>
                                                {{__("Available permissions")}}
                                            </th>
                                
                                
                                           
                                            <tbody class="role-permission">
                                               @foreach($custom_permission as $key => $group)
                                                <tr>
                                                    <td>
                                                        <b>{{ ucfirst($key) }}</b>
                                                    </td>
                                                    <td width="30%">
                                                        <label>
                                                            <input class="selectall" onclick="selectAll(this.value)" value="{{$key}}" type="checkbox">
                                                            {{__('Select All') }}
                                                        </label>
                                                    </td>
                                                    <td>
                                                        
                                                        @forelse($group as $permission)
                                                            
                                                           <label>
                                                                @if(in_array($permission->id, $permissionIds))
                                                                   <input name="permissions[]" class="permissioncheckbox {{$key}}" type="checkbox" checked value="{{ $permission->id }}">
                                                                   &nbsp; {{ucfirst($permission->name)}} &nbsp;&nbsp;
                                                                @else
                                                                    <input name="permissions[]" class="permissioncheckbox {{$key}}" type="checkbox" value="{{ $permission->id }}">
                                                                   &nbsp; {{ucfirst($permission->name)}} &nbsp;&nbsp;
                                                                @endif
                                                           </label>
                                
                                                        @empty
                                                            {{ __("No permission in this group !") }}
                                                        @endforelse
                                
                                                    </td>
                                
                                                </tr>
                                               @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-success bg-grad-4">Update</button>
                                        <input type="reset" class="btn btn-danger" value="Reset">
                                    </div>

                                </form>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


@endsection   
<script type="text/javascript">
    function changeRole(argument) {
        var base_url = window.location.origin + '/' + window.location.pathname.split ('/') [1] + '/';
        // var url =  base_url + 'urbanmop/public/role/permissions/' + argument;
        // $.ajax({
        //     url: url,
        //     method: 'Get',
        //     success:function(response)
        //     {
               
        //         $('.role-permission').html(response);
        //     },
        //     error: function(response) {
        //     }
        // });
        
        window.location = base_url + 'urbanmop/public/manage/role/' + argument;
    }
</script> 

<script>
    function selectAll(argument) {
       
        var cls = "."+argument;

        if($(cls).prop('checked')==true){
           
            $(cls).prop('checked', false);
        } else {
           
            $(cls).prop('checked', true); 
        }
        
    }
</script>





       