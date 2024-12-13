@extends('web.layout.header')
@section('title','Terms and Conditions')
@section('meta_tags')
<meta name="description" content="Read our all terms and conditions here.">
@endsection
@section('content')
    <main>
   
        <section class="pt-50  p-relative">
            <div class="container">
                <h2>Terms & Conditions</h2>
                {!! $setting?$setting->description:'' !!}
            </div>
      </section>

   </main>
@endsection