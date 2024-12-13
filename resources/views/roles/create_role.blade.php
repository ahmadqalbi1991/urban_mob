@extends('layouts.dashboard')

@section('content')

        <!--**********************************

            Content body start

        ***********************************-->

        <div class="content-body">

            <div class="row page-titles mx-0">

                    <div class="col-sm-6 p-md-0">

                        <div class="breadcrumb-range-picker">

                            <h3 class="ml-1">Add Role & Permission</h3>

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

                                <form action="{{ route('role.store') }}" method="POST">
                                    @csrf

                                    <div class="form-group row">
                                        <div class="col-lg-6">
                                            <label>Role<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="role" placeholder="Enter Role" required>
                                        </div>
                                    </div>
                                    <div class="row mt-4 form-group">
                                       <!-- @foreach($permissions as $key => $permission)
                                        <div class="col-lg-4">
                                            <label class="ml-2">
                                                <input name="permissions[]" class="permissioncheckbox" type="checkbox" value="{{ $permission->id }}">
                                               
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
                                
                                
                                           
                                            <tbody>
                                               @foreach($custom_permission as $key => $group)
                                                <tr>
                                                    <td>
                                                        <b>{{ ucfirst($key) }}</b>
                                                    </td>
                                                    <td width="30%">
                                                        @if($key)
                                                        <label>
                                                            <input class="selectall" onclick="selectAll(this.value)" value="{{$key}}" type="checkbox">
                                                            {{__('Select All') }}
                                                        </label>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        
                                                        @forelse($group as $permission)
                                
                                                           <label>
                                                               <input name="permissions[]" class="permissioncheckbox {{$key}}" type="checkbox" value="{{ $permission->id }}">
                                                               &nbsp; {{ucfirst($permission->name)}} &nbsp;&nbsp;
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
                                        <button type="submit" class="btn btn-success bg-grad-4">Create</button>
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





       