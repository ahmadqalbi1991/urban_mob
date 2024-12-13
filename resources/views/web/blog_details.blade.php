@extends('web.layout.header')
@section('title',$blog->meta_title)
@section('meta_tags')
<meta name="description" content="{{$blog->meta_description}}">
<meta name="keywords" content="{{$blog->meta_keyword}}">
@endsection


@section('content')
   <main>
   <style>
    .blog-title{
        color:#000 !important;
    }
    </style>

        <section class="pt-50 p-relative">
            <div class="container">

                <h4 class="mb-4">Blogs Detail</h4>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <img class="card-img-top" width="100%" src="{{ url('/uploads/blog/'.$blog->banner) }}" alt="{{$blog->name}}" title="{{$blog->name}}">
                            <div class="card-body">
                                <h5 class="card-title blog-title"> {{$blog->name}} </h5>
                                <p class="card-text">{!! $blog->details !!}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
 

<style type="text/css">
    

.h1, .h2, .h3, .h4, .h5, .h6{
    color:#6a6d7a !important;   
 }

 
</style>

