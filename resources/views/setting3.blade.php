@extends('layouts.dashboard')

@section('content')

        <!--**********************************

            Content body start

        ***********************************-->

        <div class="content-body">

            <div class="row page-titles mx-0">

                    <div class="col-sm-6 p-md-0">

                        <div class="breadcrumb-range-picker">

                            <h3 class="ml-1">Customer Settings</h3>

                        </div>

                    </div>

                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">

                        <a href="{{route('page.create')}}">

                            <button type="button" class="btn btn-rounded btn-primary ml-4">

                                <span class="btn-icon-left text-primary">

                                    <i class="fa fa-plus color-primary"></i> 

                                </span>
                                Add Page

                            </button>

                        </a>

                    </div>

                </div>

            <div class="container-fluid">

                @include('flash_msg')



                <div class="row">

                    <div class="col-12">

                        <div class="card">

                            <div class="card-body">

                                <div class="table-responsive">

                                    <table class="table display">

                                        <thead>

                                            <tr>

                                                <th>#</th>

                                                <th>Page</th>

                                                <th>Content</th>

                                                <th>Action</th>

                                            </tr>

                                        </thead>

                                        <tbody>

                                            @if(!empty($settings['pages']))

                                            @foreach($settings['pages'] as $key=>$value)

                                            <tr>

                                                <td>{{ ++$key }}</td>

                                                <td>{{ $value->title }}</td>

                                                <td>{{substr(strip_tags($value->description),0,100).'...' }}</td>

                                                <td>

                                                    

                                                    <button type="button" class="btn btn-outline-info btn-ft" data-toggle="modal" data-target="#editPageModal{{$key}}" title="Edit" alt="Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>

                                                    <!-- Edit Modal -->

                                                    <div class="modal fade" id="editPageModal{{$key}}">

                                                        <div class="modal-dialog modal-dialog-centered modal-xl">

                                                        <div class="modal-content">

                                                        

                                                            <!-- Modal Header -->

                                                            <div class="modal-header">

                                                            <h4 class="modal-title">Page Content ({{ $value->title }})</h4>

                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>

                                                            </div>

                                                            <div id="MSG{{ $value->id }}" class="text-success text-center"></div>

                                                            <form action="{{ route('setting.save',['id' => $value->id, 'type' =>'page']) }}" method="POST" id="settingForm{{ $value->id }}" enctype="multipart/form-data">

                                                                <!-- Modal body -->

                                                                <div class="modal-body">

                                                                    @csrf

                                                                    <div class="form-group">

                                                                        <!-- <label>Text<span class="text-danger">*</span></label> -->

                                                                        <textarea class="form-control summernote" rows="10" id="description{{ $value->id }}" name="description" required>{{ $value->description }}</textarea>

                                                                    </div>

                                                                </div>

                                                                

                                                                <!-- Modal footer -->

                                                                <div class="modal-footer">

                                                                <button type="submit" onclick="sattingSave(<?=$value->id;?>)" class="btn btn-primary">Update</button>

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

                                                <th>Page</th>

                                                <th>Content</th>

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

	          // 	alert('try later!');

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



       