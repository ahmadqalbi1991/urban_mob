<p>Dear Team UrbanMop,</p>

<p>A new service partner has registered on our website and has expressed interest in joining UrbanMop's network of trusted professionals. The company's name is {{$data->company_name}}, and they offer 
@foreach(App\SellerService::where('seller_id',$data->id)->get() as $serv)
	{{$serv->service?$serv->service->name:''}},
@endforeach
.</p>

<p>Regards,</p>