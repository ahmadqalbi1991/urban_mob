
@extends('web.layout.header')
@section('title','Urbanmop | Payment Faild')
@section('content')
<main>
   
      <section class="pt-50  p-relative">
        <div class="registration-form card">
            <h2>Sorry!</h2>
            <h4 class="text-center"> <strong>Your Payment Is Field. Contact Urbanmop</strong> </h4>
            <h6 class="text-center"><small>Your Order ID : {{$card?$card->tran_id:''}}</small></h6>            

          </div>

      </section>
   </main>

   @endsection