@extends('web.layout.header')
@section('title','Read our blogs')
@section('meta_tags')
<meta name="description" content="Find out our latest blogs here. We are cover all latest news and ideas, tips for local services that we provides to our customers.">
@endsection
@section('content')
    <main>
   <style>
    @media (min-width:767px){
        .blogs_urban img{height:250px; padding:3px}
    }

    .blog-title{
    	color:#000 !important;
    }
    </style>
        <section class="pt-50  p-relative">
            <div class="container">
                <h4 class="mb-4">Blogs</h4>
                @if($blogs && count($blogs))
                     <div class="row">
                        @foreach($blogs as $blog)
                            
                               
                            <div class="col-lg-4 col-md-6 mb-3">
                                <a href="{{url('blogs/'.$blog->slug)}}">
                                    <div class="card blogs_urban"
                                    >
                                        <img class="card-img-top " src="{{ url('/uploads/blog/'.$blog->image) }}" alt="{{$blog->name}}" title="{{$blog->name}}">
                                        <div class="card-body">
                                            <h5 class="card-title blog-title" title="{{$blog->name}}">{{ $blog->name }}..</h5>
                                            <p class="card-text">{{$blog->blogcategory?$blog->blogcategory->name:''}}</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            
                        @endforeach
                    </div>
               @else
                    <h5>No Blogs</h5>
               @endif

            </div>
        </section>

   </main>
@endsection