@extends('layouts.dashboard')
@section('content')
        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <div class="row page-titles mx-0">
                    <div class="col-sm-6 p-md-0">
                        <div class="breadcrumb-range-picker">
                            <h3 class="ml-1">Content Settings</h3>
                        </div>
                    </div>
                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                        
                    </div>
                </div>
            <div class="container-fluid">
                @include('flash_msg')


                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4>Thought Of The Day </h4>
                                <div class="table-responsive">
                                    <table class="table display">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Thought</th>
                                                <th>Langauage</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(!empty($settings['thought']))
                                            @foreach($settings['thought'] as $key=>$value)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td>{{ $value->description }}</td>
                                                <td>{{ $value->lang }}</td>
                                                <td>
                                                    
                                                    <button type="button" class="btn btn-outline-info btn-ft" data-toggle="modal" data-target="#editModal{{$key}}">Edit</button>
                                                    <!-- Edit Modal -->
                                                    <div class="modal fade" id="editModal{{$key}}">
                                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                                        <div class="modal-content">
                                                        
                                                            <!-- Modal Header -->
                                                            <div class="modal-header">
                                                            <h4 class="modal-title">Thought Of The Day</h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>
                                                            <div id="MSG{{ $value->id }}" class="text-success text-center"></div>
                                                            <form action="{{ route('setting.save',['id' => $value->id, 'type' =>'thought-of-the-day']) }}" method="POST" id="settingForm{{ $value->id }}" enctype="multipart/form-data">
                                                                <!-- Modal body -->
                                                                <div class="modal-body">
                                                                    @csrf
                                                                    <div class="form-group">
                                                                        <!-- <label>Thought<span class="text-danger">*</span></label> -->
                                                                        <textarea class="form-control" rows="5" id="thought{{ $value->id }}" name="description" required>{{ $value->description }}</textarea>
                                                                    </div>
                                                                </div>
                                                                
                                                                <!-- Modal footer -->
                                                                <div class="modal-footer">
                                                                <button type="submit" onclick="sattingSave(<?=$value->id;?>)" class="btn btn-success">Update</button>
                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                            @endif
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>#</th>
                                                <th>Thought</th>
                                                <th>Langauage</th>
                                                <th>Action</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4>Web Call To Action </h4>
                                <div class="table-responsive">
                                    <table class="table display">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Text</th>
                                                <th>Langauage</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(!empty($settings['actions']))
                                            @foreach($settings['actions'] as $key=>$value)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td>{{ strip_tags($value->description) }}</td>
                                                <td>{{ $value->lang }}</td>
                                                <td>
                                                    
                                                <button type="button" class="btn btn-outline-info btn-ft" data-toggle="modal" data-target="#editAction{{$value->id}}">Edit</button>
                                                    <!-- Edit Modal -->
                                                    <div class="modal fade" id="editAction{{$value->id}}">
                                                        <div class="modal-dialog modal-dialog-centered modal-xl">
                                                        <div class="modal-content">
                                                        
                                                            <!-- Modal Header -->
                                                            <div class="modal-header">
                                                            <h4 class="modal-title">Call-to-Action</h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>
                                                            <div id="MSG{{ $value->id }}" class="text-success text-center"></div>
                                                            <form action="{{ route('setting.save',['id' => $value->id, 'type' =>'call-to-action']) }}" id="settingForm{{ $value->id }}" method="POST" enctype="multipart/form-data">
                                                                <!-- Modal body -->
                                                                <div class="modal-body">
                                                                    @csrf
                                                                    <div class="form-group">
                                                                        <textarea class="form-control summernote" rows="5" id="common{{ $value->id }}" name="description" required>{{ $value->description }}</textarea>
                                                                    </div>
                                                                </div>
                                                                
                                                                <!-- Modal footer -->
                                                                <div class="modal-footer">
                                                                <button type="submit" onclick="sattingSave(<?=$value->id;?>)" class="btn btn-success">Update</button>
                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        </div>
                                                    </div>

                                                </td>
                                            </tr>
                                            @endforeach
                                            @endif
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>#</th>
                                                <th>Text</th>
                                                <th>Langauage</th>
                                                <th>Action</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4>App Call To Action 1</h4>
                                <div class="table-responsive">
                                    <table class="table display">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Text</th>
                                                <th>Langauage</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(!empty($settings['app-actions-1']))
                                            @foreach($settings['app-actions-1'] as $key=>$value)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td>{{ strip_tags($value->description) }}</td>
                                                <td>{{ $value->lang }}</td>
                                                <td>
                                                    
                                                <button type="button" class="btn btn-outline-info btn-ft" data-toggle="modal" data-target="#editAction{{$value->id}}">Edit</button>
                                                    <!-- Edit Modal -->
                                                    <div class="modal fade" id="editAction{{$value->id}}">
                                                        <div class="modal-dialog modal-dialog-centered modal-xl">
                                                        <div class="modal-content">
                                                        
                                                            <!-- Modal Header -->
                                                            <div class="modal-header">
                                                            <h4 class="modal-title">App-Call-to-Action</h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>
                                                            <div id="MSG{{ $value->id }}" class="text-success text-center"></div>
                                                            <form action="{{ route('setting.save',['id' => $value->id, 'type' =>'call-to-action']) }}" id="settingForm{{ $value->id }}" method="POST" enctype="multipart/form-data">
                                                                <!-- Modal body -->
                                                                <div class="modal-body">
                                                                    @csrf
                                                                    <div class="form-group">
                                                                        <textarea class="form-control summernote" rows="5" id="common{{ $value->id }}" name="description" required>{{ $value->description }}</textarea>
                                                                    </div>
                                                                </div>
                                                                
                                                                <!-- Modal footer -->
                                                                <div class="modal-footer">
                                                                <button type="submit" onclick="sattingSave(<?=$value->id;?>)" class="btn btn-success">Update</button>
                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        </div>
                                                    </div>

                                                </td>
                                            </tr>
                                            @endforeach
                                            @endif
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>#</th>
                                                <th>Text</th>
                                                <th>Langauage</th>
                                                <th>Action</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4>App Call To Action 2</h4>
                                <div class="table-responsive">
                                    <table class="table display">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Text</th>
                                                <th>Langauage</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(!empty($settings['app-actions-2']))
                                            @foreach($settings['app-actions-2'] as $key=>$value)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td>{{ strip_tags($value->description) }}</td>
                                                <td>{{ $value->lang }}</td>
                                                <td>
                                                    
                                                <button type="button" class="btn btn-outline-info btn-ft" data-toggle="modal" data-target="#editAction{{$value->id}}">Edit</button>
                                                    <!-- Edit Modal -->
                                                    <div class="modal fade" id="editAction{{$value->id}}">
                                                        <div class="modal-dialog modal-dialog-centered modal-xl">
                                                        <div class="modal-content">
                                                        
                                                            <!-- Modal Header -->
                                                            <div class="modal-header">
                                                            <h4 class="modal-title">App-Call-to-Action-2</h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>
                                                            <div id="MSG{{ $value->id }}" class="text-success text-center"></div>
                                                            <form action="{{ route('setting.save',['id' => $value->id, 'type' =>'call-to-action']) }}" id="settingForm{{ $value->id }}" method="POST" enctype="multipart/form-data">
                                                                <!-- Modal body -->
                                                                <div class="modal-body">
                                                                    @csrf
                                                                    <div class="form-group">
                                                                        <textarea class="form-control summernote" rows="5" id="common{{ $value->id }}" name="description" required>{{ $value->description }}</textarea>
                                                                    </div>
                                                                </div>
                                                                
                                                                <!-- Modal footer -->
                                                                <div class="modal-footer">
                                                                <button type="submit" onclick="sattingSave(<?=$value->id;?>)" class="btn btn-success">Update</button>
                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        </div>
                                                    </div>

                                                </td>
                                            </tr>
                                            @endforeach
                                            @endif
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>#</th>
                                                <th>Text</th>
                                                <th>Langauage</th>
                                                <th>Action</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4>App Call To Action 3</h4>
                                <div class="table-responsive">
                                    <table class="table display">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Text</th>
                                                <th>Langauage</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(!empty($settings['app-actions-3']))
                                            @foreach($settings['app-actions-3'] as $key=>$value)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td>{{ strip_tags($value->description) }}</td>
                                                <td>{{ $value->lang }}</td>
                                                <td>
                                                    
                                                <button type="button" class="btn btn-outline-info btn-ft" data-toggle="modal" data-target="#editAction{{$value->id}}">Edit</button>
                                                    <!-- Edit Modal -->
                                                    <div class="modal fade" id="editAction{{$value->id}}">
                                                        <div class="modal-dialog modal-dialog-centered modal-xl">
                                                        <div class="modal-content">
                                                        
                                                            <!-- Modal Header -->
                                                            <div class="modal-header">
                                                            <h4 class="modal-title">App-Call-to-Action-3</h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>
                                                            <div id="MSG{{ $value->id }}" class="text-success text-center"></div>
                                                            <form action="{{ route('setting.save',['id' => $value->id, 'type' =>'call-to-action']) }}" id="settingForm{{ $value->id }}" method="POST" enctype="multipart/form-data">
                                                                <!-- Modal body -->
                                                                <div class="modal-body">
                                                                    @csrf
                                                                    <div class="form-group">
                                                                        <textarea class="form-control summernote" rows="5" id="common{{ $value->id }}" name="description" required>{{ $value->description }}</textarea>
                                                                    </div>
                                                                </div>
                                                                
                                                                <!-- Modal footer -->
                                                                <div class="modal-footer">
                                                                <button type="submit" onclick="sattingSave(<?=$value->id;?>)" class="btn btn-success">Update</button>
                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        </div>
                                                    </div>

                                                </td>
                                            </tr>
                                            @endforeach
                                            @endif
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>#</th>
                                                <th>Text</th>
                                                <th>Langauage</th>
                                                <th>Action</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!--**********************************
            Content body end
        ***********************************-->


<script type="text/javascript">
    function sattingSave(id)
    {
        $('#MSG'+id).html('');
        $("#MSG"+id).show();
        
        $('#settingForm'+id).on('submit', function(event){
            event.preventDefault();
            $('#preloader').show();
            var form_data = $("#settingForm"+id).serialize()+ '&id=' + id;
             $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  }
              });
            $.ajax({
            url: "<?php echo route('setting_update') ?>",
            type: 'post',
            dataType: "json",
            data:form_data,
            // data: {
            //   id: id,
            //   description: description,
            //   _token : "{{ csrf_token() }}"
            // },
            success: function( response ) {
                //alert(response.message);
              // console.log(response);
              $('#preloader').hide();
              if(response.success==true)
              {
                $('#MSG'+id).html(response.message);
                $("#MSG"+id).delay(3200).fadeOut(300);
                //alert(response.message);
                //location.reload();
              }
              // else
              // {
              //    alert('try later!');
              // }
            }
            });
         });
        //var description=$('#description'+id).val();
        // var description=$('#description'+id+'_ifr').find('body').html();
        // if(!id || !description)
        // {
        //     alert('Required field is empty!');
        //     return false;
        // }
    }
</script>

@endsection      

       