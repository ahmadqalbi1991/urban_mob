@extends('layouts.dashboard')

@section('content')

        <!--**********************************

            Content body start

        ***********************************-->

        <div class="content-body">

            <div class="row page-titles mx-0">

                    <div class="col-sm-6 p-md-0">

                        <div class="breadcrumb-range-picker">

                            <h3 class="ml-1">Schedule</h3>

                        </div>

                    </div>

                </div>

            <div class="container-fluid">

                @include('flash_msg')

                <div class="row">

                    <div class="col-12">

                        <div class="card">

                            <div class="card-body">

                                <div class="custom-tab-2">
                                    <?php $data['active'] = 'Schedule'; ?>
                                    @include('offline.menu',$data)
                                    <form action="{{ route('step4') }}" method="post">
                                    @csrf
                                        <div class="row">

                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label>Slot Date</label>
                                                    <input type="date" name="slot_date" class="form-control slot_date" onchange="filterSlot(this.value)" value="{{ $off_line_booking->date }}" required>
                                                    <input type="hidden" value="{{date('Y-m-d')}}" class="current_date">
                                                </div>
                                            </div>

                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label>Choose The Preferred Time Slot</label>
                                                    <select name="slot_id" class="form-control select2" required>
                                                        <option value="">Select Slot</option>
                                                        @foreach($slots as $slot)
                                                        <option value="{{$slot->id}}" {{ $off_line_booking->slot_id==$slot->id?'selected':'' }}>{{$slot->name}}</option>
                                                        @endforeach
                                                    </select>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-lg-4"></div>

                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="">Booking Instructions</label>
                                                    <textarea name="booking_instruction" class="form-control" placeholder="Instructions of professional"></textarea>
                                                </div>
                                            </div>

                                        </div>
                                        
                                        <div class="row mt-4">
                                            <div class="col-lg-6">
                                                <div class="text-left">
                                                    <a href="{{ route('offline.service') }}"><button class="btn btn-primary" type="button">Back</button></a>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="text-right">
                                                   <button class="btn btn-primary" type="submit">Next</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>

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

@endsection 

@section('script')

<script>
    function filterSlot(selectDate) {
        var currentDate = $('.current_date').val();
        
        if(currentDate==selectDate){
            $.ajax({
                headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },    
                type: 'Post',
                url: "{{ url('get/slot') }}",
                data: {
                        from : 'current'
                },
                success: function (response) {
                        $('.slot_value').html('');       
                        $('.slot_value').html(response);             
                },
                error: function (response) {
                        console.log(response);
                }
            });
        } else {
            $.ajax({
                headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },    
                type: 'Post',
                url: "{{ url('get/slot') }}",
                data: {
                        from : 'no'
                },
                success: function (response) {
                        $('.slot_value').html('');       
                        $('.slot_value').html(response);          
                },
                error: function (response) {
                        console.log(response);
                }
            });
        }

    }
</script>
@endsection



       