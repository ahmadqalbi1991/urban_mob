@extends('layouts.dashboard')
@section('content')
        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <div class="row page-titles mx-0">
                    <div class="col-sm-6 p-md-0">
                        <div class="breadcrumb-range-picker">
                            <h3 class="ml-1">Website/App Settings</h3>
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
                                <h4>Common Seeting </h4>
                                <div class="table-responsive">
                                    <table class="table display">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Meta Key</th>
                                                <th>Meta Value</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(!empty($settings['common']))
                                            @foreach($settings['common'] as $key=>$value)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td>{{ $value->title }}
                                                        @if($value->title=='calling-hours')
                                                        ({{ $value->lang }})
                                                        @endif
                                                </td>
                                                <td>{{ $value->description }}</td>
                                                <td>
                                                    
                                                    <button type="button" class="btn btn-outline-info btn-ft" data-toggle="modal" data-target="#editCommon{{$key}}">Edit</button>
                                                    <!-- Edit Modal -->
                                                    <div class="modal fade" id="editCommon{{$key}}">
                                                        <div class="modal-dialog modal-dialog-centered modal-md">
                                                        <div class="modal-content">
                                                        
                                                            <!-- Modal Header -->
                                                            <div class="modal-header">
                                                            <h4 class="modal-title">{{ $value->title }}</h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>
                                                            <div id="MSG{{ $value->id }}" class="text-success text-center"></div>
                                                            <form action="{{ route('setting.save',['id' => $value->id, 'type' =>'common-setting']) }}" id="settingForm{{ $value->id }}" method="POST" enctype="multipart/form-data">
                                                                <!-- Modal body -->
                                                                <div class="modal-body">
                                                                    @csrf
                                                                    <div class="form-group">
                                                                        <textarea class="form-control" rows="3" id="common" name="description" required>{{ $value->description }}</textarea>
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
                                                <th>Meta Key</th>
                                                <th>Meta Value</th>
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
                                <h4>Banner Video </h4>
                                <div class="table-responsive">
                                    <table class="table display">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Video</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(!empty($settings['video']))
                                            @foreach($settings['video'] as $key=>$value)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td>
                                               
                                                <video width="320" height="240" controls>
                                                    <source src="{{ asset($value->file) }}" type="video/mp4">
                                                    Your browser does not support the video tag.
                                                    </video>
                                               
                                                </td>
                                                <td>
                                                    
                                                    <button type="button" class="btn btn-outline-info btn-ft" data-toggle="modal" data-target="#videoModal{{$key}}">Edit</button>
                                                    <!-- Edit Modal -->
                                                    <div class="modal fade" id="videoModal{{$key}}">
                                                        <div class="modal-dialog modal-dialog-centered modal-sm">
                                                        <div class="modal-content">
                                                        
                                                            <!-- Modal Header -->
                                                            <div class="modal-header">
                                                            <h4 class="modal-title">Home Banner Video</h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>
                                                            <form action="{{ route('setting.video.save',$value->id) }}" method="POST" enctype="multipart/form-data">
                                                                <!-- Modal body -->
                                                                <div class="modal-body">
                                                                    @csrf
                                                                    <div class="form-group">
                                                                        <label>Video<span class="text-danger">* (Max-size:15 MB)</span></label>
                                                                        <input type="file" name="file" class="form-control-file border">
                                                                        <input type="hidden" name="old_file" class="form-control" value="{{$value->file}}">
                                                                    </div>
                                                                </div>
                                                                
                                                                <!-- Modal footer -->
                                                                <div class="modal-footer">
                                                                <button type="submit" class="btn btn-success">Update</button>
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
                                                <th>Video</th>
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
                                <h4>Advertisement </h4>
                                <div class="table-responsive">
                                    <table class="table display">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Image</th>
                                                <th>Section</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(!empty($settings['ads']))
                                            @foreach($settings['ads'] as $key=>$value)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td><a href="{{asset($value->file)}}" target="_blank"><img src="{{asset($value->file)}}"  style="max-width:350px;max-height:250px;"></a></td>
                                                <td>{{ $value->title }}</td>
                                                <td>
                                                    
                                                    <button type="button" class="btn btn-outline-info btn-ft" data-toggle="modal" data-target="#adsModal{{$key}}">Edit</button>
                                                    <!-- Edit Modal -->
                                                    <div class="modal fade" id="adsModal{{$key}}">
                                                        <div class="modal-dialog modal-dialog-centered modal-sm">
                                                        <div class="modal-content">
                                                        
                                                            <!-- Modal Header -->
                                                            <div class="modal-header">
                                                            <h4 class="modal-title">Advertisement Image </h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>
                                                            <form action="{{ route('setting.ads.save',$value->id) }}" method="POST" enctype="multipart/form-data">
                                                                <!-- Modal body -->
                                                                <div class="modal-body">
                                                                    @csrf
                                                                    <div class="form-group">
                                                                        <label>Image<span class="text-danger">*
                                                                        @if($value->title=='ads-banner')
                                                                        (Size 450*475)
                                                                        @elseif($value->title=='ads-web')
                                                                        (Size 1450*255)
                                                                        @elseif($value->title=='ads-app')
                                                                        (Size 750*250)
                                                                        @endif
                                                                        </span></label>
                                                                        <input type="file" name="file" class="form-control-file border">
                                                                        <input type="hidden" name="old_file" class="form-control" value="{{$value->file}}">
                                                                        @if($value->file)
                                                                            <img src="{{asset($value->file)}}"  style="width:250px;height:auto;">
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                
                                                                <!-- Modal footer -->
                                                                <div class="modal-footer">
                                                                <button type="submit" class="btn btn-success">Update</button>
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
                                                <th>Image</th>
                                                <th>Section</th>
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

       