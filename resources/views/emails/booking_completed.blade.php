
<h5>Dear 
	@if($data && $data->user)
	    {{$data->user?$data->user->name:''}}
	@else
	    'No Name'
	@endif
,</h5>


<p>We appreciate your relationship with UrbanMop.com and in an effort to improve your overall experience with us, we request your participation in rating the service and professional.</p>


<p>The objective of the survey is to seek your feedback on UrbanMop service quality, services and staff interaction.</p>

<?php 
	$base_url = URL::to('/'); 
	$url  = $base_url.'/booking/review/'.$data->encrypt;
	if($url){
		$url = $url;
	} else {
		$url = 'https://9a4yhhhzdd7.typeform.com/to/FoG6FNhq';
	}
?>
<a href="{{$url}}" target="_blank">https://www.urbanmop.com</a>

<!-- <a href="https://9a4yhhhzdd7.typeform.com/to/FoG6FNhq" target="_blank">https://www.urbanmop.com</a> -->

<p>We value your feedback and look forward to your response. </p>

<p>Regards,</p>
<p>Urbanmop.com</p>