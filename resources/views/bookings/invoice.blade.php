  <!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Order Invoice</title>
    <style>
      .header {
          padding: 2%;
          background-color: #e9ecef;
      }
      .bill {
        padding: 2%;
      }
      .products {
        padding: 2%;
      }
    </style>
  </head>
  <body class="html-content" style="font-size: 12px;">
    
    <?php $setting = App\HomeSetting::first(); ?>
    <table class="table">
      <thead class="thead-light">
        <tr>
          <th scope="col">
            @if($setting?$setting->admin_side_logo:'')

            <img src="{{asset('/uploads/home/'.$setting->admin_side_logo)}}" alt="logo" style="height: 50px; width: 50px;">

            @endif
            <h6>UrbanMop</h6>
            
            Email : booknow@urbanmop.com<br>
            Phone : +9710526188291 / +9710585814007
          </th>
          <th scope="col" style="text-align: right;">
            <h5 class="text-right">Invoice</h5>
              Booking ID : {{$card->tran_id}}<br> 
              Creation Date : {{ date('d F Y', strtotime($card->updated_at))}}
          </th>
        </tr>
      </thead>
    </table>    
    <div class="row bill">
      <div class="col-lg-12">
        <p>
          Bill to : <br>
          <b>Customer : </b>{{$card->user?$card->user->name:''}}<br>
          <b>Email : </b>{{$card->user?$card->user->email:''}}<br>
          <b>Phone : </b>+971{{$card->user?$card->user->phone:''}}<br>
          <b>Alternate No. : </b>{{$card->alternative_number?'+971':''}}{{$card->alternative_number}}<br>
          <b>Address : </b>

          @if(is_numeric($card->address_id))

            {{$card->address?$card->address->building:''}}, {{$card->address?$card->address->flat_no:''}}, {{$card->address?$card->address->address_type:''}}, 
            @if($card->address && $card->address->locality_info)
              {{$card->address->locality_info?$card->address->locality_info->name:''}}, 
            @endif

            @if($card->address && $card->address->city)
              {{$card->address->city?$card->address->city->name:''}}, 
            @endif
            {{$card->address?$card->address->address:''}}

          @elseif(json_decode($card->address_id))

            {{json_decode($card->address_id)->building}}, {{json_decode($card->address_id)->flat_no}}, {{json_decode($card->address_id)->address_type}}

            @if(json_decode($card->address_id) && json_decode($card->address_id)->locality)
            {{json_decode($card->address_id)->locality}},
            @endif

            @if(json_decode($card->address_id) && json_decode($card->address_id)->city_name)
            {{json_decode($card->address_id)->city_name}},
            @endif

            {{json_decode($card->address_id)->address}}

          @else

          @endif

          <br><br>         
          <b>Service : </b>
          @if(is_numeric($card->service_id))
              {{$card->service?$card->service->name:''}}
          @else
              {{$card->service_name}}
          @endif
          <br>
          <b>Category : </b>{{$card->category?$card->category->name:'No Category'}}<br>
          <b>Slot Date : </b>{{ date('d F Y', strtotime($card->date))}}<br>
          <b>Slot : </b>{{$card->slot?$card->slot->name:''}}<br>
          <b>Booking Instructions : </b>{{$card?$card->note:''}}
          @if($card->payment_moad=='Card' && $card->paymentTranId)
          <b>Payment Transaction ID : </b>
            @if(isset($card->paymentTranId))
                {{$card->paymentTranId}}
            @else
                {{$card->tabby_payment_response_id}}
            @endif
          @endif
        </p>
      </div>
    </div>

    <div class="row products">
      <div class="col-lg-12">
        <table class="table">
          <thead class="thead-light">
            <tr>
              <th scope="col">#</th>
              <th scope="col">Sub Category</th>
              <th scope="col">Attribute</th>
              <th scope="col">Attribute Item</th>
              <th scope="col">Quantity</th>
              <th scope="col">Price</th>
            </tr>
          </thead>
          <tbody>
            <?php $total = '0'; $subtotal = '0'; ?>
            @foreach($card->card_attribute as $key => $items)
            <tr>
              <td>{{ ++$key }}</td>
              <td>{{$items->main_sub_cat?$items->main_sub_cat->name:''}}</td>
              <td>{{$items->attribute_name}}</td>
              <td>{{$items->attribute_item_name}}</td>
              <td>{{$items->attribute_qty}}</td>
              <td>{{ Session::get('currencies') }} {{$items->attribute_price}}</td>
            </tr>
            <?php
              $total += $items->attribute_qty*$items->attribute_price;
              $subtotal += $items->attribute_qty*$items->attribute_price;
            ?>
            @endforeach
            <tr>
              <td colspan="4"></td>
              <td>Sub Total</td>
              <td>{{ Session::get('currencies') }} {{$subtotal}}</td>
            </tr>
            @if($card && $card->material_status=='Apply')
            <tr>
              <td colspan="4"></td>
              <td>Material Charge</td>
              <td>{{ Session::get('currencies') }} {{$card->material_charge}}</td>
            </tr>
            <?php $total += $card->material_charge; ?>
            @endif
            @if($card && count($card->card_addon))
            <?php $addonAmt = $card->card_addon->sum('value'); ?>
            <tr>
              <td colspan="4"></td>
              <td>Addon</td>
              <td>{{ Session::get('currencies') }} {{$addonAmt}}</td>
            </tr>
            <?php $total += $addonAmt ?>
            @endif
           

            <?php
              if(App\CardCoupon::where('card_id',$card->id)->exists()){
                $coupon = App\CardCoupon::where('card_id',$card?$card->id:'')->first();
              } else {
                $coupon = App\Coupon::where('id',$card?$card->coupon_id:'')->first();
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
            <tr>
              <td colspan="4"></td>
              <td>Coupon</td>
              <td>{{ Session::get('currencies') }} {{$coupon_Amt}}</td>
            </tr>

            @if($card->tip_id)
            <tr>
              <td colspan="4"></td>
              <td>Tip</td>
              <td>{{ Session::get('currencies') }} {{$card->tip_id}}</td>
            </tr>
            <?php $total += $card->tip_id ?>
            @endif

            @if($card->offline_charge)
            <tr>
              <td colspan="4"></td>
              <td>Convenience Fee (+)</td>
              <td>{{ Session::get('currencies') }} {{price_format($card->offline_charge)}}</td>
              <?php $total += $card->offline_charge; ?>
            </tr>
            @endif
            @if($card->offline_discount)
            <tr class="text-danger">
              <td colspan="4"></td>
              <td>Discount (-)</td>
              <td>{{ Session::get('currencies') }} {{price_format($card->offline_discount)}}</td>
              <?php $total -= $card->offline_discount; ?>
            </tr>
            @endif

             @if($card && $card->cod_charge)
            <tr>
              <td colspan="4"></td>
              <td>Cash Surcharge</td>
              <td>{{ Session::get('currencies') }} {{$card->cod_charge}}</td>
            </tr>
            <?php $total += $card->cod_charge; ?>
            @endif

            <tr>
              <td colspan="4"></td>
              <td>Grand Total</td>
              <td>{{ Session::get('currencies') }} {{price_format($total)}}</td>
            </tr>

          </tbody>
        </table>
      </div>
    </div>
    <hr>
    <?php 
      $url = URL::to('/').'/'.'booking/view/'.$card->id;
    ?>
    <input type="hidden" class="url" value="{{$url}}">

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
<script type="text/javascript" src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>
<script>
    $(document).ready(function () {
        // CreatePDFfromHTML();
    });

    function CreatePDFfromHTML() {
        $(".html-content").show();
        var HTML_Width = $(".html-content").width();
        var HTML_Height = $(".html-content").height();
        var top_left_margin = 15;
        var PDF_Width = HTML_Width + (top_left_margin * 2);
        var PDF_Height = (PDF_Width * 1.5) + (top_left_margin * 2);
        var canvas_image_width = HTML_Width;
        var canvas_image_height = HTML_Height;

        var totalPDFPages = Math.ceil(HTML_Height / PDF_Height) - 1;

        html2canvas($(".html-content")[0]).then(function (canvas) {
            var imgData = canvas.toDataURL("image/jpeg", 1.0);
            var pdf = new jsPDF('p', 'pt', [PDF_Width, PDF_Height]);
            pdf.addImage(imgData, 'JPG', top_left_margin, top_left_margin, canvas_image_width, canvas_image_height);
            for (var i = 1; i <= totalPDFPages; i++) { 
                pdf.addPage(PDF_Width, PDF_Height);
                pdf.addImage(imgData, 'JPG', top_left_margin, -(PDF_Height*i)+(top_left_margin*4),canvas_image_width,canvas_image_height);

                // pdf.setPage(i)
                // pdf.text('Page ' + String(i) + ' of ' + String(totalPDFPages), pdf.internal.pageSize.width / 2, PDF_Height, {
                // align: 'center'
                // })

            }
            pdf.save("Booking Invoice.pdf");
            var href = $('.url').val();
            window.location=href;
        });
    }

</script>
  </body>
</html>