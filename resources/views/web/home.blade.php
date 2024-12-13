@extends('web.layout.header')
@section('title','Get Expert Professional Services at Home in Dubai, UAE')
@section('meta_tags')
<meta name="description" content="Urbanmop is your one-stop destination to get trusted expert professionals near you right at your doorstep, covering all your home services, home repair, and beauty needs.">
@endsection
@section('content')
      <!-- hero area start -->
      <section class="tp-hero-area p-relative mt-30">
         <div class="container">
            <div class="row">
               <div class="col-lg-12">
                  <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
                       <div class="carousel-indicators">
                         <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                         <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
                         <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
                       </div>
                       <div class="carousel-inner">
                        @if($home_setting->home_banner)
                         <div class="carousel-item active">
                           <a href="{{$home_setting->home_banner_link?$home_setting->home_banner_link:'javascript:'}}">
                              <img src="{{ url('uploads/banner/'.$home_setting->home_banner) }}" title="{{ $home_setting->first_title }}" alt="{{ $home_setting->first_title }}">
                           </a>
                         </div>
                         @endif
                         @if($home_setting->second_home_banner)
                         <div class="carousel-item">
                           <a href="{{$home_setting->second_home_banner_link?$home_setting->second_home_banner_link:'javascript:'}}">
                              <img src="{{ url('uploads/banner/'.$home_setting->second_home_banner) }}" title="{{ $home_setting->second_title }}" alt="{{ $home_setting->second_title }}">
                           </a>
                         </div>
                         @endif
                         @if($home_setting->third_home_banner)
                         <div class="carousel-item">
                           <a href="{{$home_setting->third_home_banner_link?$home_setting->third_home_banner_link:'javascript:'}}">
                              <img src="{{ url('uploads/banner/'.$home_setting->third_home_banner) }}" title="{{ $home_setting->third_title }}" alt="{{ $home_setting->third_title }}">
                           </a>
                         </div>
                         @endif
                       </div>
                       <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                         <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                         <span class="visually-hidden">Previous</span>
                       </button>
                       <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                         <span class="carousel-control-next-icon" aria-hidden="true"></span>
                         <span class="visually-hidden">Next</span>
                       </button>
                     </div>
               </div>
            </div>
         </div>
      </section>
      <!-- hero area end -->


          <section class="tp-team-3-area pt-40 pb-0 p-relative">
          <div class="container">
              <h3 class="mb-30 text-left">Services</h3>
              <div class="row">
                  @foreach($services as $key => $service)
                  <div class="col-sm-6 col-md-6 col-lg-3 col-xl-3">
                      <a href="{{ route('service.details', $service->slug) }}">
                          <div class="tp-team-2-thumb mb-30">
                              @if($service && $service->thumbnail_img)
                              <img src="{{ url('uploads/service/'.$service->thumbnail_img) }}" alt="{{$service->name}}" title="{{$service->name}}">
                              @else
                              <img src="{{ url('web/Banner-not-found.jpg') }}" alt="{{$service->name}}" title="{{$service->name}}">
                              @endif
                              <div class="tp-team-2-inner">
                                  <h5 class="text-white">{{$service->name}}</h5>
                              </div>
                          </div>
                      </a>
                  </div>
                  @endforeach
              </div>
          </div>
      </section>

      @if($featured_services_status)

         <!-------------Start recommended Services------->
<!--          <section class="tp-team-3-area p-relative">
            <div class="container">
               <h3 class="mb-30">Recommended Services</h3>
               <div class="service-active splide wow fadeInUp" data-wow-duration="1s"
                  data-wow-delay=".3s">
                     <div class="splide__track">
                        <div class="splide__list">
                  @foreach($featured_services as $key => $featured)
                  <div class="splide__slide">
                              <div class="tp-service-item p-relative">
                            
                                 <div class="item-shape">
                                 <a href="{{route('service.details',$featured->id)}}">
                           <img src="{{ url('uploads/service/featured_banner/'.$featured->featured_banner) }}" style="max-height: 100px;" alt="">
                           </a>     
                  </div>  </div> </div>
                  @endforeach

               </div>    </div> </div>    
            </div>
         </section> -->


        <!-------------Start recommended Services------->
      <section class="tp-team-3-area pt-80 pb-80 p-relative">
         <div class="container">
            <h3 class="mb-30">Recommended Services</h3>
            <div class="row">
               <div class="col-xl-12">
                  <div class="service-active splide wow fadeInUp" data-wow-duration="1s"
                  data-wow-delay=".3s">
                     <div class="splide__track">
                        <div class="splide__list">
                           @foreach($featured_services as $key => $featured)
                           <div class="splide__slide">
                              <div class="tp-service-item p-relative">
                                 <div class="item-shape">
                                    <a href="{{route('service.details',$featured->slug)}}">
                                       <img src="{{ url('uploads/service/featured_banner/'.$featured->featured_banner) }}" style="max-height: 257px;" alt="{{$featured->name}}" title="{{$featured->name}}">
                                    </a> 
                                 </div>                                
                              </div>
                           </div>
                           @endforeach
                                                                         
                        </div>
                     </div> 
                  </div>
               </div>
               </div>
          
         </div>
      </section>


<!-------------end recommended Services------->


   <!-------------end recommended Services------->
      @endif      

      <section class="pt-40 pb-140 p-relative" id="download-app-mobile">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <div class="onstore_section">
                     <div class="row">
                        <div class="col-lg-6">
                           <h3 class="orange-gre">Download UrbanMop App</h3>
                           <p>Book cleaning services effortlessly, anytime, anywhere. Stay tuned!</p>
                           
                            <div class="d-flex flex-column flex-sm-row">
                            <div class="mb-3 mb-sm-0"> 
                            <img src="{{ url('web/assets/img/scan-me-qr.png') }}" style="width: 285px;" class="img-fluid">
                            </div>
                            
                            <div class="d-flex flex-column flex-sm-row">
                            <div class="mb-3 mb-sm-0">
                            <a href="https://play.google.com/store/apps/details?id=com.urbanmop" target="_blank" class="d-block mb-2 mb-sm-0 me-sm-2">
                            <img src="{{ url('web/assets/img/urbanmop-on-playstore.png') }}" class="img-fluid">
                            </a>
                            <a href="https://apps.apple.com/in/app/urbanmop/id6474144613" target="_blank" class="d-block">
                            <img src="{{ url('web/assets/img/urbanmop-on-appstore.png') }}" class="img-fluid">
                            </a>
                            </div>
                            </div>

                            </div>


                        </div>
                        <div class="col-lg-5 offset-lg-1 po-rel">
                           <div class="mobile-app-bg">
                              <img src="{{ url('web/assets/img/mobile-app-bg.svg') }}">
                           </div>
                          <div class="mobile-app"><img src="{{ url('web/assets/img/mobile-app.png') }}"></div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </section>


  

<!-------------end recommended Services------->
 
<!-- arban work start -->
<section id="testimonialBubble">
   <div class="container">
      <div class="row">
         <div class="col-lg-12">
            <div class="tp-testimonial-2-section-title-wrapper text-center">
               <h3 class="mb-40 text-black" >How UrbanMop Works? <br/>
                  <p class="fs-20 mt-3" >Just Book Your slot in less than 1 minute and leave rest to us!</p>
               </h3>
            </div>
         </div>
      </div>
   
      <div class="row">
         <div class="col-lg-3 text-center" >
            <img src="{{ url('web/assets/img/home page images/user.gif') }}" alt="Trained Professional" title="Trained Professional" class="w-100px h-100px" >
            <h4>Trained Professional</h4>
            <p class="fs-18">Highly trained and trusted professionals,recruited<br/> after multiple screening<br/> rounds </p>   
         </div>
         <div class="col-lg-3 text-center" >
            <img src="{{ url('web/assets/img/home page images/payment.gif') }}" alt="Pay Later" title="Pay Later" class="w-100px h-100px" >
            <h4>Pay Later</h4>
            <p  class="fs-18">No need of advance<br/> payment. Pay only after<br/> the home services get<br/> completed </p>   
         </div>
         <div class="col-lg-3 text-center" >
            <img src="{{ url('web/assets/img/home page images/support.gif') }}" alt="Customer Support" title="Customer Support" class="w-100px h-100px" >
            <h4>Customer Support</h4>
            <p  class="fs-18">High quality and <br/>availability of support<br/> team to provide excellent<br/> customer service</p>   
         </div>
         <div class="col-lg-3 text-center" >
            <img src="{{ url('web/assets/img/home page images/like.gif') }}" alt="Quality Products" title="Quality Products" class="w-100px h-100px" >
            <h4>Quality Products</h4>
            <p  class="fs-18">We do bring our own<br/> cleaning products &<br/> accept special requests to<br/> give better services </p>   
         </div>
      </div>
   </div>
</section>
<!-- arban work end -->


<!-- testimonial area start -->
      <section id="testimonialBubble" class="tp-testimonial-2-area p-relative pt-50 pb-20">
         <div class="container">
            <div class="row">
               <div class="col-lg-12">
                  <div class="text-start">
                     <h4 class="mb-20">Customer Reviews <br/></h4>
                        <p class="fs-20 text-black mb-4">Join The hundreds of satisfied customers who have booked our cleaning services.
                        Don't miss out, book now!</p>
                     
                  </div>
               </div>
            </div>
         
            <div class="row">
               <div class="col-lg-4">
                  <div class="tp-testimonial-2-wrapper">
                     <div class="row mb-3" >
                        <div class="col-2"><i class="fa-sharp fa-solid fa-circle-j fs-40 text-green" ></i></div>
                       <div class="col-10 text-black">Thomas Wright<br/>
                        <div id="position" class="review_star" >
                           <span class="star-icon full">&starf;</span>
                           <span class="star-icon full">&starf;</span>
                           <span class="star-icon full">&starf;</span>
                           <span class="star-icon">&starf;</span>
                           <span class="star-icon">&starf;</span>
                       </div>
                     </div> 
                     </div>
                       <p>I recently had my sofa cleaned by UrbanMop team, and I must say they did an excellent job! The team arrived on time, were very professional, and used high-quality cleaning products. My sofa looks brand new now. I highly recommend their services to everyone in Dubai.</p>
                           <span class="t-rel-t">Sofa Cleaning</span>
                  </div>
               </div>
               <div class="col-lg-4">
                  <div class="tp-testimonial-2-wrapper">
                     <div class="row mb-3" >
                        <div class="col-2"><i class="fa-sharp fa-solid fa-circle-r fs-40 text-green" ></i></div>
                       <div class="col-10 text-black" >Deepak Reddy<br/>
                        <div id="position" class="review_star" >
                           <span class="star-icon full">&starf;</span>
                           <span class="star-icon full">&starf;</span>
                           <span class="star-icon full">&starf;</span>
                           <span class="star-icon">&starf;</span>
                           <span class="star-icon">&starf;</span>
                       </div>
                     </div> 
                     </div>
                       <p>I recently used urbanmop’s car wash service, and I'm impressed with the results. The team arrived promptly and did an excellent job cleaning both the interior and exterior of my car. They were thorough, used high-quality products, and paid attention to detail. My car looks shiny and brand new. I highly recommend their car wash service to all car owners.</p>
                       <span class="t-rel-t">Car Wash</span>
                  </div>
               </div>
               <div class="col-lg-4">
                  <div class="tp-testimonial-2-wrapper">
                     <div class="row mb-3" >
                        <div class="col-2"><i class="fa-sharp fa-solid fa-circle-n fs-40 text-green"></i></div>
                       <div class="col-10 text-black" >Jennifer Davis<br/>
                        <div id="position" class="review_star" >
                           <span class="star-icon full">&starf;</span>
                           <span class="star-icon full">&starf;</span>
                           <span class="star-icon full">&starf;</span>
                           <span class="star-icon">&starf;</span>
                           <span class="star-icon">&starf;</span>
                       </div>
                     </div> 
                     </div>
                       <p>Recently booked a spa service from this company, and it was a relaxing experience. The spa professionals were skilled and created a serene atmosphere.</p>
                       <span class="t-rel-t">Spa Service</span>
                  </div>
               </div>

            </div>
         </div>
      </section>
<!-- testimonial area end -->

@endsection


<style type="text/css">
  
  @media only screen and (max-width: 768px) and (min-width: 200px)  {
        .tp-hero-area{
          margin-top: 10px !important;
        }

        .p-relative {
          padding-left: 0px !important;
        }

        .tp-hero-area .col-lg-12{
          padding-right:5px !important;
          padding-left:5px !important;
        }

        .tp-team-3-area {
          padding-top: 15px !important;
        }

        .tp-team-3-area .mb-30 {
          margin-bottom: 15px !important;
        }

        .tp-team-3-area .tp-team-2-thumb img {
          height: 120px !important;
          width: 185px !important;
        }
      
      .tp-team-3-area .col-sm-6{
      width: 50% !important;
      max-width: 50% !important;
      padding-right:5px !important;
      padding-left: 5px !important;
      }

      .tp-team-3-area .tp-team-2-inner {
        padding: 0 10px !important;
        } 
   
      .tp-team-3-area{
        padding-bottom: 0px !important;
      }


      #download-app-mobile{
        padding-bottom: 30px !important;
        padding-top: 10px !important;
      }

      #download-app-mobile .col-md-12{
        padding-left: 10px !important;
        padding-right: 10px !important;
      }

      .tp-testimonial-2-area {
      padding-bottom: 0px !important;
        }

      .profile_img{
        display: none !important;
      }
      
  }

</style>