<html class="no-js" lang="en">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title> Order confirmation </title>
<meta name="robots" content="noindex,nofollow" />
<meta name="viewport" content="width=device-width; initial-scale=1.0;" />
<style type="text/css">
  @import url(https://fonts.googleapis.com/css?family=Open+Sans:400,700);
  body { margin: 0; padding: 0; background: #e1e1e1; }
  div, p, a, li, td { -webkit-text-size-adjust: none; }
  .ReadMsgBody { width: 100%; background-color: #ffffff; }
  .ExternalClass { width: 100%; background-color: #ffffff; }
  body { width: 100%; height: 100%; background-color: #e1e1e1; margin: 0; padding: 0; -webkit-font-smoothing: antialiased; }
  html { width: 100%; }
  p { padding: 0 !important; margin-top: 0 !important; margin-right: 0 !important; margin-bottom: 0 !important; margin-left: 0 !important; }
  .visibleMobile { display: none; }
  .hiddenMobile { display: block; }

  @media only screen and (max-width: 600px) {
  body { width: auto !important; }
  table[class=fullTable] { width: 96% !important; clear: both; }
  table[class=fullPadding] { width: 85% !important; clear: both; }
  table[class=col] { width: 45% !important; }
  .erase { display: none; }
  }

  @media only screen and (max-width: 420px) {
  table[class=fullTable] { width: 100% !important; clear: both; }
  table[class=fullPadding] { width: 85% !important; clear: both; }
  table[class=col] { width: 100% !important; clear: both; }
  table[class=col] td { text-align: left !important; }
  .erase { display: none; font-size: 0; max-height: 0; line-height: 0; padding: 0; }
  .visibleMobile { display: block !important; }
  .hiddenMobile { display: none !important; }
  }
</style>


<!-- Header -->
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="fullTable" bgcolor="#e1e1e1">
  <tr>
    <td height="20"></td>
  </tr>
  <tr>
    <td>
      <table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="fullTable" bgcolor="#ffffff" style="border-radius: 10px 10px 0 0;">
        <tr class="hiddenMobile">
          <td height="40"></td>
        </tr>
        <tr class="visibleMobile">
          <td height="30"></td>
        </tr>

        <tr>
          <td>
            <table width="480" border="0" cellpadding="0" cellspacing="0" align="center" class="fullPadding">
              <tbody>
                <tr>
                  <td>
                    <table width="220" border="0" cellpadding="0" cellspacing="0" align="left" class="col">
                      <tbody>
                        <tr>
                          <td align="left"> <img src="https://www.urbanmop.com/web/assets/img/logo/dsadsdsdurban-mop-category-update-image.png" width="100" height="100" alt="logo" border="0" /></td>
                        </tr>
                        <tr class="hiddenMobile">
                          <td height="40"></td>
                        </tr>
                        <tr class="visibleMobile">
                          <td height="20"></td>
                        </tr>
                        <tr>
                          <td style="font-size: 12px; color: #5b5b5b; font-family: 'Open Sans', sans-serif; line-height: 18px; vertical-align: top; text-align: left;">
                            <br> 
                            	<b>Customer : </b>{{$data->user?$data->user->name:''}}<br>
                              <b>Email : </b>{{$data->user?$data->user->email:''}}<br>
                              <b>Phone : </b>+971{{$data->user?$data->user->phone:''}}<br>
                              <b>Address : </b><br>
                              @if(is_numeric($data->address_id))
                                <strong>Address Type - {{$data->address?$data->address->address_type:''}}</strong>
                                <br>
                                Flat No. {{$data->address?$data->address->flat_no:''}}, {{$data->address?$data->address->building:''}}, 
                                <br>
                               
                                @if($data->address && $data->address->city)
                                {{$data->address->city?$data->address->city->name:''}}, 
                                @endif
                                <br>
                              
                              {{$data->address?$data->address->address:''}}
                              <br><br>         

                              <a style="text-decoration: underline; color:blue; font-weight: 700;" 
                              href="https://www.google.com/maps/search/?api=1&query={{$data->address?$data->address->latitude:''}},{{$data->address?$data->address->longitude:''}}"> Click here for location </a>
                              <br><br>
                               
                              @else

                              <strong>Address Type - {{json_decode($data->address_id)->address_type}}</strong>
                              <br>
                              Flat No. {{json_decode($data->address_id)->flat_no}}, {{json_decode($data->address_id)->building}}, 
                              <br>
                              {{json_decode($data->address_id)->locality}},

                              {{json_decode($data->address_id)->city_name}},

                              
                              <br>
                              {{json_decode($data->address_id)->address}}
                              <br><br>

                              <a style="text-decoration: underline; color:blue; font-weight: 700;" 
                              href="https://www.google.com/maps/search/?api=1&query={{json_decode($data->address_id)->latitude}},{{json_decode($data->address_id)->longitude}}"> Click here for location </a>
                              <br><br> 

                              @endif
                              <b>Creation Date : </b>{{$data?$data->created_at:''}}<br>
                              <b>Service : </b>{{$data->service?$data->service->name:''}}<br>
                              <b>Category : </b>{{$data->category?$data->category->name:'No Category'}}<br>
                              <b>Slot Date : </b>{{ date('d F Y', strtotime($data->date))}}<br>
                              <b>Slot : </b>{{$data->slot?$data->slot->name:''}}<br>
                              <b>Booking Instructions : </b>{{$data?$data->note:''}}

                          </td>
                          
                        </tr>
                      </tbody>
                    </table>
                    <table width="220" border="0" cellpadding="0" cellspacing="0" align="right" class="col">
                      <tbody>
                        <tr class="visibleMobile">
                          <td height="20"></td>
                        </tr>
                        <tr>
                          <td height="5"></td>
                        </tr>
                        <tr>
                          <td style="font-size: 21px; color: #ff0000; letter-spacing: -1px; font-family: 'Open Sans', sans-serif; line-height: 1; vertical-align: top; text-align: right;">
                            Invoice
                          </td>
                        </tr>
                        <tr>
                        <tr class="hiddenMobile">
                          <td height="50"></td>
                        </tr>
                        <tr class="visibleMobile">
                          <td height="20"></td>
                        </tr>
                        <tr>
                          <td style="font-size: 12px; color: #5b5b5b; font-family: 'Open Sans', sans-serif; line-height: 18px; vertical-align: top; text-align: right;">
                            <small>Booking ID :</small> {{$data->tran_id}}<br />
                            <small>Creation Date : {{ date('d F Y', strtotime($data->created_at))}}</small><br>
                            <!-- <a href="{{ $data->payment_link }}" target=”_blank” style="background-color: #e8ac18; border: none; color: white; padding: 2px 13px; text-align: center; text-decoration: none; display: inline-block; font-size: 11px; margin: 4px 2px; cursor: pointer; margin-top: 50%; border-radius: 7px; box-shadow: 4px 4px 3px rgb(0 0 0 / 41%);">Pay This Invoice</a> -->

                            <a href="{{ $data->payment_link }}" style="
                            	background: rgb(255,177,0) !important;
                        background: linear-gradient(90deg, rgba(255,177,0,1) 0%, rgba(255,138,0,1) 50%) !important;
                        border: none !important;
                        color: white !important;
                        padding: 10px 35px !important;
                        text-align: center !important;
                        text-decoration: none !important;
                        display: inline-block !important;
                        font-size: 16px !important;
                        margin: 4px 2px !important;
                        cursor: pointer !important;
                        margin-top: 10% !important;
                        border-radius: 30px !important;
                        -webkit-box-shadow: 0px 0px 5px 2px rgba(0,0,0,0.13) !important;
                        -moz-box-shadow: 0px 0px 5px 2px rgba(0,0,0,0.13) !important;
                        box-shadow: 0px 0px 5px 2px rgba(0,0,0,0.13) !important;
                        font-weight: 600 !important;">Pay This Invoice</a><br><small>(Kindly ignore if already paid.)</small>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                </tr>
              </tbody>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<!-- /Header -->
<!-- Order Details -->
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="fullTable" bgcolor="#e1e1e1">
  <tbody>
    <tr>
      <td>
        <table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="fullTable" bgcolor="#ffffff">
          <tbody>
            <tr>
            <tr class="hiddenMobile">
              <td height="60"></td>
            </tr>
            <tr class="visibleMobile">
              <td height="40"></td>
            </tr>
            <tr>
              <td>
                <table width="480" border="0" cellpadding="0" cellspacing="0" align="center" class="fullPadding">
                  <tbody>
                    <tr>
                      <th style="font-size: 12px; font-family: 'Open Sans', sans-serif; color: #5b5b5b; font-weight: normal; line-height: 1; vertical-align: top; padding: 0 10px 7px 0;" width="35%" align="left">
                        Service Category
                      </th>
                      <th style="font-size: 12px; font-family: 'Open Sans', sans-serif; color: #5b5b5b; font-weight: normal; line-height: 1; vertical-align: top; padding: 0 0 7px;" align="left">
                        <small>Service Name</small>
                      </th>
                      <th style="font-size: 12px; font-family: 'Open Sans', sans-serif; color: #5b5b5b; font-weight: normal; line-height: 1; vertical-align: top; padding: 0 0 7px;" align="left">
                        <small>Service Item</small>
                      </th>
                      <th style="font-size: 12px; font-family: 'Open Sans', sans-serif; color: #5b5b5b; font-weight: normal; line-height: 1; vertical-align: top; padding: 0 0 7px;" align="center">
                        Quantity
                      </th>
                      <th style="font-size: 12px; font-family: 'Open Sans', sans-serif; color: #1e2b33; font-weight: normal; line-height: 1; vertical-align: top; padding: 0 0 7px;" align="right">
                        Price
                      </th>
                    </tr>
                    <tr>
                      <td height="1" style="background: #bebebe;" colspan="5"></td>
                    </tr>
                    <?php $total = '0'; $subtotal = '0'; ?>
					@foreach($data->card_attribute as $key => $items)
                    <tr>
                      <td height="10" colspan="5"></td>
                    </tr>

                    <tr>
                      <td style="font-size: 12px; font-family: 'Open Sans', sans-serif; color: #ff0000;  line-height: 18px;  vertical-align: top; padding:10px 0;" class="article">
                       {{$items->main_sub_cat?$items->main_sub_cat->name:''}}
                      </td>

                      <td style="font-size: 12px; font-family: 'Open Sans', sans-serif; color: #646a6e;  line-height: 18px;  vertical-align: top; padding:10px 0;"><small>{{$items->attribute_name}}</small></td>

                      <td style="font-size: 12px; font-family: 'Open Sans', sans-serif; color: #646a6e;  line-height: 18px;  vertical-align: top; padding:10px 0;"><small>{{$items->attribute_item_name}}</small></td>
                      <td style="font-size: 12px; font-family: 'Open Sans', sans-serif; color: #646a6e;  line-height: 18px;  vertical-align: top; padding:10px 0;" align="center">{{$items->attribute_qty}}</td>
                      <td style="font-size: 12px; font-family: 'Open Sans', sans-serif; color: #1e2b33;  line-height: 18px;  vertical-align: top; padding:10px 0;" align="right">AED {{$items->attribute_qty*$items->attribute_price}}</td>
                    </tr>
                    <tr>
                      <td height="1" colspan="5" style="border-bottom:1px solid #e4e4e4"></td>
                    </tr>
                    <?php
    		              	$total += $items->attribute_qty*$items->attribute_price;
    		              	$subtotal += $items->attribute_qty*$items->attribute_price;
    		            ?>
                    @endforeach

                  </tbody>
                </table>
              </td>
            </tr>
            <tr>
              <td height="20"></td>
            </tr>
          </tbody>
        </table>
      </td>
    </tr>
  </tbody>
</table>
<!-- /Order Details -->
<!-- Total -->
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="fullTable" bgcolor="#e1e1e1">
  <tbody>
    <tr>
      <td>
        <table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="fullTable" bgcolor="#ffffff">
          <tbody>
            <tr>
              <td>

                <!-- Table Total -->
                <table width="480" border="0" cellpadding="0" cellspacing="0" align="center" class="fullPadding">
                  <tbody>
                    <tr>
                      <td style="font-size: 12px; font-family: 'Open Sans', sans-serif; color: #646a6e; line-height: 22px; vertical-align: top; text-align:right; ">
                        Subtotal
                      </td>
                      <td style="font-size: 12px; font-family: 'Open Sans', sans-serif; color: #646a6e; line-height: 22px; vertical-align: top; text-align:right; white-space:nowrap;" width="80">
                        AED {{$subtotal}}
                      </td>
                    </tr>
                    <!-- <tr>
                      <td style="font-size: 12px; font-family: 'Open Sans', sans-serif; color: #646a6e; line-height: 22px; vertical-align: top; text-align:right; ">
                        Shipping
                      </td>
                      <td style="font-size: 12px; font-family: 'Open Sans', sans-serif; color: #646a6e; line-height: 22px; vertical-align: top; text-align:right; white-space:nowrap;" width="80">
                        AED 00
                      </td>
                    </tr> -->
                    @if($data && $data->material_status=='Apply')
                      <tr>
                          <td style="font-size: 12px; font-family: 'Open Sans', sans-serif; color: #646a6e; line-height: 22px; vertical-align: top; text-align:right; ">Material Charge</td>
                          <td style="font-size: 12px; font-family: 'Open Sans', sans-serif; color: #646a6e; line-height: 22px; vertical-align: top; text-align:right; white-space:nowrap;" width="80">
                            <small>AED {{$data->material_charge}}</small>
                          </td>
                      </tr>
                      <?php $total += $data->material_charge ?>
                    @endif                    
                 
                    <?php
                        if(App\CardCoupon::where('card_id',$data->id)->exists()){
                          $coupon = App\CardCoupon::where('card_id',$data?$data->id:'')->first();
                        } else {
                          $coupon = App\Coupon::where('id',$data?$data->coupon_id:'')->first();
                        }
    		              
    		              if($coupon && $data->coupon_id){
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

                    @if($data->coupon_id && $coupon_Amt)
                    <tr>
                      <td style="font-size: 12px; font-family: 'Open Sans', sans-serif; color: #646a6e; line-height: 22px; vertical-align: top; text-align:right; ">
                        Coupon 
                      </td>
                      <td style="font-size: 12px; font-family: 'Open Sans', sans-serif; color: #646a6e; line-height: 22px; vertical-align: top; text-align:right; white-space:nowrap;" width="80">
                        AED {{$coupon_Amt}}
                      </td>
                    </tr>
                    @endif
                    

            		@if($data->tip_id)
            			<tr>
                    	<td style="font-size: 12px; font-family: 'Open Sans', sans-serif; color: #646a6e; line-height: 22px; vertical-align: top; text-align:right; ">Tip</td>
                    	<td style="font-size: 12px; font-family: 'Open Sans', sans-serif; color: #646a6e; line-height: 22px; vertical-align: top; text-align:right; white-space:nowrap;" width="80">
                      	<small>AED {{$data->tip_id}}</small>
                    	</td>
                  </tr>
                	<?php $total += $data->tip_id ?>
            		@endif

                @if($data && $data->cod_charge)
                  <tr>
                      <td style="font-size: 12px; font-family: 'Open Sans', sans-serif; color: #646a6e; line-height: 22px; vertical-align: top; text-align:right; ">Cash Surcharge</td>
                      <td style="font-size: 12px; font-family: 'Open Sans', sans-serif; color: #646a6e; line-height: 22px; vertical-align: top; text-align:right; white-space:nowrap;" width="80">
                        <small>AED {{$data->cod_charge}}</small>
                      </td>
                  </tr>
                  <?php $total += $data->cod_charge; ?>
                @endif

                @if($data && $data->offline_charge)
                  <tr>
                      <td style="font-size: 12px; font-family: 'Open Sans', sans-serif; color: #646a6e; line-height: 22px; vertical-align: top; text-align:right; ">Charge</td>
                      <td style="font-size: 12px; font-family: 'Open Sans', sans-serif; color: #646a6e; line-height: 22px; vertical-align: top; text-align:right; white-space:nowrap;" width="80">
                        <small>AED {{$data->offline_charge}}</small>
                      </td>
                  </tr>
                  <?php $total += $data->offline_charge; ?>
                @endif

                @if($data && $data->offline_discount)
                  <tr>
                      <td style="font-size: 12px; font-family: 'Open Sans', sans-serif; color: #646a6e; line-height: 22px; vertical-align: top; text-align:right; color: red;">Discount</td>
                      <td style="font-size: 12px; font-family: 'Open Sans', sans-serif; color: #646a6e; line-height: 22px; vertical-align: top; text-align:right; white-space:nowrap; color: red;" width="80">
                        <small>AED {{$data->offline_discount}}</small>
                      </td>
                  </tr>
                  <?php $total -= $data->offline_discount; ?>
                @endif

            		 <tr>
                      <td style="font-size: 12px; font-family: 'Open Sans', sans-serif; color: #000; line-height: 22px; vertical-align: top; text-align:right; ">
                        <strong>Grand Total </strong>
                      </td>
                      <td style="font-size: 12px; font-family: 'Open Sans', sans-serif; color: #000; line-height: 22px; vertical-align: top; text-align:right; ">
                        <strong>AED {{price_format($total)}}</strong>
                      </td>
                    </tr>
            		
                  </tbody>
                </table>
                <!-- /Table Total -->

              </td>
            </tr>
          </tbody>
        </table>
      </td>
    </tr>
  </tbody>
</table>
<!-- /Total -->

<!-- /Information -->
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="fullTable" bgcolor="#e1e1e1">

  <tr>
    <td>
      <table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="fullTable" bgcolor="#ffffff" style="border-radius: 0 0 10px 10px;">
        <tr>
          <td>
            <table width="480" border="0" cellpadding="0" cellspacing="0" align="center" class="fullPadding">
              <tbody>
                <tr>
                  <td style="font-size: 12px; color: #5b5b5b; font-family: 'Open Sans', sans-serif; line-height: 18px; vertical-align: top; text-align: left;">
                   Thankyou for using urbanmop. Have a nice day.
                  </td>
                </tr>
                
              </tbody>
            </table>
          </td>
        </tr>
        <tr class="spacer">
          <td height="50"></td>
        </tr>

      </table>
    </td>
  </tr>
  <tr>
    <td height="20"></td>
  </tr>
</table>
</body>

</html>