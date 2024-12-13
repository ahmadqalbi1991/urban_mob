@extends('layouts.dashboard')

@section('content')

        <!--**********************************

            Content body start

        ***********************************-->

        <div class="content-body">



            <div class="container-fluid">

                <div class="row">

                    <div class="col-xl-6 col-xxl-12">

                        <div class="row">

                            <div class="col-sm-6 col-xxl-6 col-xl-6">

                                <div class="card bg-grad-1">

                                    <div class="card-body pb-0">

                                        <div class="row justify-content-between">

                                            <div class="col-auto">

                                                <h4 class="text-white mb-3">Users</h4>

                                            </div>

                                            <div class="col-auto">

                                                <h2>{{$total_users}}</h2>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="col-sm-6 col-xxl-6 col-xl-6">

                                <div class="card bg-grad-3">

                                    <div class="card-body pb-0">

                                        <div class="row justify-content-between">

                                            <div class="col-auto">

                                                <h4 class="text-white mb-3">Vendors</h4>

                                            </div>

                                            <div class="col-auto">

                                                <h2>{{$total_vendors}}</h2>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="col-sm-6 col-xxl-6 col-xl-6">

                                <div class="card bg-grad-2">

                                    <div class="card-body pb-0">

                                        <div class="row justify-content-between">

                                            <div class="col-auto">

                                                <h4 class="text-white mb-3">Services</h4>

                                            </div>

                                            <div class="col-auto">

                                                <h2>{{$service}}</h2>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="col-sm-6 col-xxl-6 col-xl-6">

                                <div class="card bg-grad-4">

                                    <div class="card-body pb-0">

                                        <div class="row justify-content-between">

                                            <div class="col-auto">

                                                <h4 class="text-white mb-3">Orders</h4>

                                            </div>

                                            <div class="col-auto">

                                                <h2>{{$orders}}</h2>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-12">

                        <div class="card">

                            <div class="card-body">

                                <h4 class="card-title">Recent Registered Users</h4>

                                <div class="table-responsive">

                                    <table class="table verticle-middle table-responsive-lg mb-0">

                                        <thead>

                                            <tr>

                                                <th scope="col">#</th>

                                                <th scope="col">Name</th>

                                                <th scope="col">Email</th>

                                                <th scope="col">Phone</th>

                                                <th scope="col">Registered</th>

                                                <th scope="col">Role</th>

                                            </tr>

                                        </thead>

                                        <tbody>

                                        @foreach($users as $key=>$value)

                                            <tr>

                                                <td>{{ ++$key }}</td>

                                                <td>{{ $value->name }}</td>

                                                <td>{{ $value->email }}</td>

                                                <td>{{ $value->phone }}</td>

                                                <td>{{ changeDateFormate($value->created_at) }}</td>

                                                <td>

                                                    <span class="badge mb-2 mb-xl-0 badge-pill bg-grad-4">{{ucfirst($value->role)}}</span>

                                                </td>

                                            </tr>

                                            @endforeach

                                        </tbody>

                                    </table>

                                    <span>

                                        {{$users->appends(request()->input())->links()}}

                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!--**********************************

            Content body end

        ***********************************-->

@endsection      



       