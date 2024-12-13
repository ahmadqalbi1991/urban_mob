@extends('web.layout.header')
@section('title','Urbanmop | My Profile')
@section('content')
<style>
    .wrapper {
        background: var(--white);
        padding: 2rem;
        max-width: 576px;
        width: 100%;
        border-radius: .75rem;
        box-shadow: var(--shadow);
        text-align: center;
    }
    .wrapper h3 {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }
    .rating {
        display: flex;
        justify-content: center;
        align-items: center;
        grid-gap: .5rem;
        font-size: 2rem;
        color: var(--yellow);
        margin-bottom: 2rem;
    }
    .rating .star {
        cursor: pointer;
    }
    .rating .star.active {
        opacity: 0;
        animation: animate .5s calc(var(--i) * .1s) ease-in-out forwards;
        color: #eab42f;
    }

    @keyframes animate {
        0% {
            opacity: 0;
            transform: scale(1);
        }
        50% {
            opacity: 1;
            transform: scale(1.2);
        }
        100% {
            opacity: 1;
            transform: scale(1);
        }
    }


    .rating .star:hover {
        transform: scale(1.1);
    }
    .btn-group {
        display: flex;
        grid-gap: .5rem;
        align-items: center;
    }
    .btn-group .btn {
        padding: .75rem 1rem;
        border-radius: .5rem;
        border: none;
        outline: none;
        cursor: pointer;
        font-size: .875rem;
        font-weight: 500;
    }
    .btn-group .btn.submit {
        background: var(--blue);
        color: var(--white);
    }
    .btn-group .btn.submit:hover {
        background: var(--blue-d-1);
    }
    .btn-group .btn.cancel {
        background: var(--white);
        color: var(--blue);
    }
    .btn-group .btn.cancel:hover {
        background: var(--light);
    }
</style>
<link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
<main>
    <section class="pt-50  p-relative">
        <div class="container">
            <div class="card">
	            <div style="p-1" class="text-center">
	   
	   				<form action="{{ route('review.store') }}" method="POST" enctype="multipart/form-data">
         			@csrf
                        <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                        <input type="hidden" name="service_id" value="{{ $booking->service_id }}">
                        <input type="hidden" name="vendor_id" value="{{ $booking->accept_user_id }}">
                        <input type="hidden" name="customer_id" value="{{ $booking->user_id }}">
					    <div class="row d-flex justify-content-center">
						    <div class="col-md-6">
                                <div class="wrapper">
                                    <h3>Share Your Booking Experience</h3>
                                    
                                        <div class="rating">
                                            <input type="number" name="rating" hidden>
                                            <i class='bx bx-star star' style="--i: 0;"></i>
                                            <i class='bx bx-star star' style="--i: 1;"></i>
                                            <i class='bx bx-star star' style="--i: 2;"></i>
                                            <i class='bx bx-star star' style="--i: 3;"></i>
                                            <i class='bx bx-star star' style="--i: 4;"></i>
                                        </div>
                                        <textarea name="opinion" placeholder="Your opinion..."></textarea>
                                                                            
                                </div>
                            </div>
                            <div class="text-center mb-4">
                                <button type="submit" class="btn btn-primary" id="head-call-first">Submit</button>
                            </div> 
                        </div>

	                </form>  

	            </div>
         	</div> 
      	</div>
    </section>
</main>
	
@endsection

@section('script')

<script>
    const allStar = document.querySelectorAll('.rating .star')
    const ratingValue = document.querySelector('.rating input')

    allStar.forEach((item, idx)=> {
        item.addEventListener('click', function () {
            let click = 0
            ratingValue.value = idx + 1

            allStar.forEach(i=> {
                i.classList.replace('bxs-star', 'bx-star')
                i.classList.remove('active')
            })
            for(let i=0; i<allStar.length; i++) {
                if(i <= idx) {
                    allStar[i].classList.replace('bx-star', 'bxs-star')
                    allStar[i].classList.add('active')
                } else {
                    allStar[i].style.setProperty('--i', click)
                    click++
                }
            }
        })
    })
</script>

@endsection