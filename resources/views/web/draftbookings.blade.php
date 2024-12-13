@extends('web.layout.header')
@section('title','Urbanmop | Orders')
@section('content')

<main>
   
   <section class="pt-50  p-relative">
      <div class="container">
         @if(count($cards))
         @foreach($cards as $key => $card)
            <div class="card mb-4 p-3" >
               <div class="row text-left" >
                  <div class="col-12">
                     <div class="row" >
                        <div class="col-6">
                           @if($card->service && $card->service->thumbnail_img)
                           <img src="{{ url('uploads/service/'.$card->service->thumbnail_img) }}" class="img-fluid rounded-start w-60px h-60px" >
                           @else
                           <img src="{{ url('web/Banner-not-found.jpg') }}" class="img-fluid rounded-start w-60px h-60px" >
                           @endif
                           <h6>{{$card->service?$card->service->name:''}}</h6>
                        </div>
                        <div class="col-6">
                           <h5 class="mb-0">Creation Date : {{ date('d F Y', strtotime($card->updated_at))}}</h5>
                           <h5 class="mb-0">Date : {{ date('d F Y', strtotime($card->date))}}</h5>
                           <h5>Slot&nbsp;&nbsp;&nbsp;: {{$card->slot?$card->slot->name:''}}</h5>
                        </div>
                     </div>
                  </div>
                  <div class="col-6">
                     
                     <div class="row mt-10">
                        <div class="col-6">
                           <h6>Booking id :</h6>
                            <h6>Transaction ID : </h6>
                        </div>
                        <div class="col-6">
                           <h6>{{$card->id}}</h6>
                           <h6>{{$card->tran_id}}</h6>
                        </div>
                        </div>


                        <div class="row mt-10">
                           <div class="col-6">Status : </div>
                           <div class="col-6">
                              
                                <span class="text-warning">{{ $card->payment_status }}</span>
                           </div>
                        </div>

                        <div class="row mt-10 mb-10">
                           <div class="col-6">Payment Mode : </div>
                           <div class="col-6">
                              
                              @if($card->payment_moad=='Cash')
                                 {{$card->payment_moad}}
                                 @if($card->payment_collected=='Yes')
                                 <small>(Paid)</small>
                                 @else
                                 <small>(Pending)</small>
                                 @endif
                              @else
                                 {{$card->payment_moad}}
                              @endif
                           </div>
                        </div>
                        
                  </div>
                  <div class="col-6">
                     <div class="row mt-10">
                        <div class="col-6">Contact No : </div>
                        <div class="col-6">
                           {{$card->user->phone?'+971':''}}{{$card->user?$card->user->phone:''}}
                        </div>
                     </div>
         
                        <div class="row mt-10">
                           <div class="col-6">Additional No : </div>
                        <div class="col-6">{{$card->alternative_number?'+971':''}}{{$card->alternative_number}}</div>
                        </div>
         
                        <div class="row mt-10">
                           <div class="col-6">Booking Instructions : </div>
                        <div class="col-6">{{$card->note}}</div>
                        </div>
                  </div>
                  <div class="row" style="padding-bottom: 20px !important;">
                     <div class="col-3">Location : </div>
                     <div class="col-9 ml-2">
                        <a style="text-decoration: underline; color:blue; font-weight: 700;" target="_blank" 
                        href="https://www.google.com/maps/search/?api=1&query={{$card->address?$card->address->latitude:''}},{{$card->address?$card->address->longitude:''}}"> Click here for location </a>
                                <br><br>
                        <strong>Address Type - {{$card->address?$card->address->address_type:''}}</strong>
                         <br>
                         Flat No. {{$card->address?$card->address->flat_no:''}}, {{$card->address?$card->address->building:''}}, 
                         <br>
                         {{$card->address?$card->address->address:''}}
                         

                     </div>
                  </div>
                  <br> 
                  <br>
                  <div class="col-12">
                     <table class="table table-striped">
                       <thead>
                         <tr>
                           <th scope="col">#</th>
                           <th scope="col">Attribute</th>
                           <th scope="col">Attribute Item</th>
                           <th scope="col">Qty</th>
                           <th scope="col">Total</th>
                         </tr>
                       </thead>
                       <tbody>
                        <?php $sub_total = '0'; $total = '0'; ?>
                        @if(isset($card->card_attribute) && count($card->card_attribute))
                           @foreach($card->card_attribute as $key => $card_atr)
                            <tr>
                              <th>{{ ++$key }}</th>
                              @if($card_atr->service_type=='Maid')
                              <td>{{$card_atr->attribute_name}}
                              <td>{{$card_atr->attribute_item_name}}</td>
                              @else
                              <td>{{$card_atr->attribute_name}}</td>
                              <td>{{$card_atr->attribute_item_name}}</td>
                              @endif

                              <td>{{$card_atr->attribute_qty}}</td>
                             
                              <td>AED {{$card_atr->attribute_price*$card_atr->attribute_qty}}</td>
                            </tr>
                         <?php
                           $sub_total += $card_atr->attribute_price*$card_atr->attribute_qty;
                           $total += $card_atr->attribute_price*$card_atr->attribute_qty;
                           ?>
                           @endforeach
                        @endif

                        @if($card && $card->material_status=='Apply')
                        <tr>
                           <th colspan="3"></th>
                           <td>Material Charge</td>
                           <td>AED {{$card->material_charge}}</td>
                        </tr>
                        <?php $total += $card->material_charge ?>
                        <?php $sub_total += $card->material_charge ?>
                        @endif

                        <tr>
                           <th colspan="3"></th>
                           <td>Sub Total</td>
                           <td>AED {{$sub_total}}</td>
                        </tr>

                        

                        <?php
                           if(App\CardCoupon::where('card_id',$card->id)->exists()){
                              $coupon = App\CardCoupon::where('card_id',$card?$card->id:'')->first();
                           } else {
                              $coupon = App\Coupon::find($card->coupon_id);
                           }
                          
                           if($coupon){
                              $amount = $coupon->amount;
                              if($coupon->type=='Amt'){
                                  $total -= $amount;
                                  $coupon_Amt = $amount;
                              } else {
                                  $per = ($amount / 100) * $total;
                                  
                                  if($per>$coupon->max_amount){
                                      $coupon_Amt = $coupon->max_amount;
                                  } else {
                                      $coupon_Amt = price_format($per);
                                  }
                                  $total -= $coupon_Amt;
                              }
                            } else {
                              $coupon_Amt = '00';
                            }
                         ?>
                           <tfoot>                           
                              
                              @if($card->coupon_id)
                              <tr>
                                 <th colspan="3"></th>
                                 <td>Coupon</td>
                                 <td>AED {{$coupon_Amt}}</td>
                              </tr>
                              
                              @endif

                              @if($card->tip_id)
                              <tr>
                                 <th colspan="3"></th>
                                 <td>Tip</td>
                                 <td>AED {{$card->tip_id}}</td>
                              </tr>
                              <?php $total += $card->tip_id ?>
                              @endif

                              @if($card->payment_moad=='Cash' && $card->cod_charge)
                              <tr>
                                 <th colspan="3"></th>
                                 <td>Cash Surcharge</td>
                                 <td>AED {{$card->cod_charge}}</td>
                              </tr>
                              <?php $total += $card->cod_charge ?>
                              @endif

                              <tr>
                                 <th colspan="3"></th>
                                 <td>Total</td>
                                 <td>AED {{price_format($total)}}</td>
                              </tr>
                              
                           </tfoot>
                       </tbody>
                     </table>
                  </div>
                  <div class="col-12">
                     <div class="row mt-15">
                           <div class="col-4">
                           <h5>AED {{$total}} 
                              @if($card->payment_moad=='Cash')
                                 @if($card->payment_collected=='Yes')
                                    <span class="text-success"> Paid</span> 
                                 @else
                                    <span class="text-warning"> Pending</span> 
                                 @endif
                              @else
                                 @if($card->payment_status=='True')
                                    <span class="text-success"> Paid</span> 
                                 @else
                                    <span class="text-warning"> Pending</span> 
                                 @endif
                              @endif
                              <i class="fa-sharp fa-solid fa-circle-check"></i>
                           </h5>
                           <p>{{$card->tran_id}}</p>
                           @if($coupon)
                           <h6 class="text-success">Offer applied: {{$coupon?$coupon->code:''}}</h6>
                           @endif
                        </div>
                        <div class="col-4">
                           @if($card->status=='Pending')
                           <h5>Want to Modify or Cancel?</h5>
                           <h5><a href="tel:+971-52-618-8291"> <i class="fa-solid fa-circle-phone"></i> Contact Support</a></h5>
                           @endif
                        </div>
                        <div class="col-4">

                        </div>
                       </div>
                  </div>

               </div> 
            </div>
         @endforeach
         @else
            
            <div class="card">
              <div class="card-body">
                <div class="text-center">
                  <h5>You Have No Bookings</h5>
               </div>
              </div>
            </div>
         @endif
      </div>
   </section>
</main>	
<input type="hidden" value="{{date('Y-m-d')}}" class="current_date">
@endsection

@section('script')
<script>
   function filterSlot(selectDate) {
      var currentDate = jQuery('.current_date').val();
      
      if(currentDate==selectDate){
         jQuery.ajax({
             headers: {
                      'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                 },    
             type: 'Post',
             url: "{{ url('get/slot') }}",
             data: {
                  from : 'current'
             },
             success: function (response) {
                    jQuery('.slot_value').html('');       
                    jQuery('.slot_value').html(response);             
             },
             error: function (response) {
                   console.log(response);
             }
         });
      } else {
         jQuery.ajax({
             headers: {
                      'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                 },    
             type: 'Post',
             url: "{{ url('get/slot') }}",
             data: {
                  from : 'no'
             },
             success: function (response) {
                    jQuery('.slot_value').html('');       
                    jQuery('.slot_value').html(response);          
             },
             error: function (response) {
                   console.log(response);
             }
         });
      }

   }
</script>
<script language="javascript">
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0');
        var yyyy = today.getFullYear();

        today = yyyy + '-' + mm + '-' + dd;
        $('.slot_date').attr('min',today);
    </script>
@endsection
