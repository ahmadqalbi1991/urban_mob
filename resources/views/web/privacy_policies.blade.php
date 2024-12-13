@extends('web.layout.header')
@section('title','Privacy Policy')
@section('meta_tags')
<meta name="description" content="Read our entire privacy policy here.">
@endsection
@section('content')
    <main>
   
        <section class="pt-50  p-relative">
            <div class="container">
                <h2>Privacy Policy</h2>
                {!! $setting?$setting->description:'' !!}
            </div>
      </section>

   </main>
@endsection