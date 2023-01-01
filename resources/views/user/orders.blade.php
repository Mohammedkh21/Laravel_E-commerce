@extends('user.layout')

@section('nav')

@endsection
@section('cart')




@endsection

@section('content')

    <div class="container-fluid" style="height: 1000px">
        <!-- DataTales Example -->

        <div class="alert  aalert" style="display: none; " role="alert"></div>



        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Orders</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive" style="min-height: 400px">
                    <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">

                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered dataTable" id="dataTable" width="100%"
                                       cellspacing="0" role="grid" aria-describedby="dataTable_info"
                                       style="width: 100%;">
                                    <thead>
                                    <tr role="row">
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="dataTable"
                                            rowspan="1" colspan="1" aria-sort="ascending"
                                            aria-label="Name: activate to sort column descending" style="width: 150px;">
                                            order id
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                            colspan="1" aria-label="Position: activate to sort column ascending"
                                            style="width: 150px;">products
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                            colspan="1" aria-label="Position: activate to sort column ascending"
                                            style="width: 150px;">pay method
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                            colspan="1" aria-label="Office: activate to sort column ascending"
                                            style="width: 150px;">status
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                            colspan="1" aria-label="Age: activate to sort column ascending"
                                            style="width: 150px;">price
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                            colspan="1" aria-label="Start date: activate to sort column ascending"
                                            style="width: 150px;">address
                                        </th>
                                    </tr>
                                    </thead>
                                    @foreach($orders as $order)
                                        <tr class="odd "  >
                                            <td class="sorting_1">{{ $order->id }}</td>
                                            <td class="sorting_1">
                                                <ul class="navbar-nav mr-auto">
                                                    <li class="nav-item dropdown">
                                                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown{{ $order->id }}" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            products
                                                        </a>
                                                        <div class="dropdown-menu" aria-labelledby="navbarDropdown{{ $order->id }}">
                                                            @foreach($order->products_data as $product)
                                                                <span class="dropdown-item d-flex align-items-center" href="#">
                                                                    <img width="60px" height="60px" src="{{ asset('storage/images/'.$product->photo) }}"  class="img-fit size-60px rounded lazyloaded" >
                                                                    <span class="minw-0 pl-2 flex-grow-1">
                                                                        <span class="fw-600 mb-1 text-truncate-2">
                                                                                {{ $product->name  }}
                                                                        </span>
                                                                        <span class="">{{ $product->quantity }}x</span>
                                                                        <span class="">{{ $product->currencie->symbol .' '.  $product->price }}</span>
                                                                    </span>
                                                                </span>
                                                            @endforeach

                                                        </div>
                                                    </li>

                                                </ul>
                                            </td>
                                            <td>{{ str_replace('_',' ',$order->pay_method) }}</td>
                                            @if($order->status == '1')
                                                <td>
                                                    <div class="col-lg-6 mb-4">
                                                        <div class="card bg-success text-white shadow"
                                                             style="text-align: center;width: 100px;">
                                                            received
                                                        </div>
                                                    </div>
                                                </td>
                                            @else
                                                <td>
                                                    <div class="col-lg-6 mb-4">
                                                        <div class="card bg-danger text-white shadow"
                                                             style="text-align: center;width: 100px;">
                                                            not received
                                                        </div>
                                                    </div>
                                                </td>
                                            @endif
                                            <td>${{ $order->price }}</td>
                                            <td>{{ $order->address }}</td>
                                        </tr>
                                    @endforeach

                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection



