<table class="table">
    <thead>
        <tr>
            <th scope="col">Attribute</th>
            <th scope="col">Attribute Item</th>
            <th scope="col">Price</th>
            <th scope="col" class="text-right">Qty</th>
            <th scope="col" class="text-right total-w">Total</th>
        </tr>
    </thead>
    <tbody>
        @php $grand_total = '0'; $main_total = '0'; @endphp
        @foreach($attribute_items as $key => $item)
        <tr>
            <th scope="row">{{ $item->attribute_name }}</th>
            <th scope="row">{{ $item->attribute_item_name }}</th>
            <td>AED {{ $item->attribute_price }}</td>
            <td class="text-right">{{ $item->attribute_qty }}</td>
            <td class="text-right">AED {{ $item->attribute_price*$item->attribute_qty }}</td>
        </tr>
        @php 
            $grand_total += $item->attribute_price*$item->attribute_qty; 
            $main_total += $item->attribute_price*$item->attribute_qty; 
        @endphp
        @php 
            $main_total += $off_line_booking->material_charge;
        @endphp
        @endforeach
        <tr class="text-right">
            <td colspan="4">Service Cost</td>
            <td>AED {{$grand_total}}</td>
        </tr>
        @if($off_line_booking->material_status=='Apply')
        <tr class="text-right">
            <td colspan="4">Material Charge</td>
            <td>AED {{ $off_line_booking->material_charge??'00' }}</td>
            @php $grand_total += $off_line_booking->material_charge; @endphp
        </tr>
        @endif
        <tr class="text-right">
            <td colspan="2"></td>
            <td>        
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <button class="btn btn-outline-secondary pay_tip btn-w" onclick="pay_tip()" type="button">Pay Tip</button>
                    </div>
                    <input type="text" class="form-control tip_value pay_amount borders special_car" id="myInput" placeholder="Tip" value="{{ $off_line_booking->tip_id }}">
                </div>
                <p class="tip_value_error text-danger"></p>
            </td>
            <td>Tip</td>
            <td>AED {{ $off_line_booking->tip_id??'00' }}</td>
            @php $grand_total += $off_line_booking->tip_id; @endphp
        </tr>
        <tr class="text-right">
            <td colspan="2"></td>
            <td>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <button class="btn btn-outline-secondary pay_charge btn-w" onclick="pay_charge()" type="button">Pay Convenience Fee</button>
                    </div>
                    <input type="text" class="form-control charge_value borders" id="myInputt" placeholder="Convenience Fee" value="{{ $off_line_booking->offline_charge }}">
                </div>
                <p class="charge_value_error text-danger"></p>
            </td>
            <td>Convenience Fee</td>
            <td>(+) AED {{ $off_line_booking->offline_charge??'00' }}</td>
            @php $grand_total += $off_line_booking->offline_charge; @endphp
        </tr>
        <tr class="text-right text-danger">
            <td colspan="2"></td>
            <td>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <button class="btn btn-outline-secondary pay_discount btn-w" onclick="pay_discount()" type="button">Pay Discount</button>
                    </div>
                    <input type="text" class="form-control discount_value borders" id="myInputth" placeholder="Discount" value="{{ $off_line_booking->offline_discount }}">
                </div>
                <p class="discount_value_error text-danger"></p>
            </td>
            <td>Discount</td>
            <td>(-) AED {{ $off_line_booking->offline_discount??'00' }}</td>
            @php $grand_total -= $off_line_booking->offline_discount; @endphp
        </tr>
        <?php
            $coupon = App\CardCoupon::where('card_id',$off_line_booking?$off_line_booking->id:'')->first();
            if($coupon){
            $amount = $coupon->amount;
            if($coupon->type=='Amt'){
                $main_total -= $amount;
                $coupon_Amt = $amount;
            } else {
                $per = ($amount / 100) * $main_total;
                
                if($per>$coupon->max_amount){
                $coupon_Amt = $coupon->max_amount;
                } else {
                $coupon_Amt = price_format($per);
                }
            }
            } else {
                $coupon_Amt = '00';
            }
        ?>
        <tr class="text-right">
            <td colspan="2"></td>
            <td>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <button class="btn btn-outline-secondary pay_discount btn-w" onclick="pay_Coupon()" type="button">Coupon</button>
                    </div>
                    <input type="text" class="form-control coupon_value borders" placeholder="Coupon" value="{{$coupon?$coupon->code:''}}">
                </div>
                <p class="coupon_value_error text-danger"></p>
                <p class="coupon_value_success text-success"></p>
            </td>
            <td>Coupon</td>
            <td>(-) AED {{$coupon_Amt}} </td>
            @php $grand_total -= $coupon_Amt; @endphp
        </tr>
        <tr class="text-right">
            <td colspan="4"><b>Grand Total</b></td>
            <td><b>AED  {{$grand_total}}</b></td>
            <input type="hidden" name="g_total" value="{{$grand_total}}">
        </tr>
    </tbody>
</table>

<script>
        $(document).ready(function() {
       
            $('.special_car').on('keypress', function(e) {
            
                var phone = jQuery('.special_car').val();
                console.log(phone);
                var regex = new RegExp("^[0-9\b]+$");
                var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
                // for 10 digit number only
                if (phone.length > 5) {
                    e.preventDefault();
                    return false;
                }
                
                if (regex.test(str)) {
                    return true;
                }
                e.preventDefault();
                return false;
            });

    });
    </script>
    <script>
        window.onload = () => {
            const myInput = document.getElementById('myInput');
            myInput.onpaste = e => e.preventDefault();

            const myInputt = document.getElementById('myInputt');
            myInputt.onpaste = e => e.preventDefault();

            const myInputth = document.getElementById('myInputth');
            myInputth.onpaste = e => e.preventDefault();
        }
    </script>