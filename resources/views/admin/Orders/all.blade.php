@extends('dashboard_admin_layout.dashboard')

@section('content')

    <div class="container-fluid">
        <!-- DataTales Example -->

        <div class="alert alert-success  aalert" style="display: none; " role="alert"></div>



        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6>
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
                                            style="width: 150px;">user
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
                                    <tbody>
                                    @foreach($orders as $order)
                                        <tr class="odd "  >
                                            <td class="sorting_1">{{ $order->id }}</td>
                                            <td class="sorting_1">{{ $order->user->name }}</td>
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
                                                <td>
                                                    <div order_id="{{ $order->id }}" class="col-lg-6 mb-4 ReceivedOreder">
                                                        @if($order->status == '1')
                                                        <div class="card bg-success text-white shadow"
                                                             style="text-align: center;width: 100px;">received</div>
                                                        @else
                                                        <div class="card bg-danger text-white shadow order{{ $order->id }}"
                                                             style="text-align: center;width: 100px;">not received</div>
                                                       @endif
                                                    </div>
                                                </td>

                                            <td>${{ $order->price }}</td>
                                            <td>{{ $order->address }}</td>
                                        </tr>
                                    @endforeach

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



@section('ajax')
    <script>


        $(document).on('click', '.ReceivedOreder', function (e) {

            var order_id =  $(this).attr('order_id');

            var status =  $(this).children('div').text() ;  console.log(status == 'not received');
            if(status == 'not received'){
                $.ajax({
                    type: 'post',
                    url: "{{ route('admin.MakeOrderAsComplete') }}",
                    data: {'_token': "{{csrf_token()}}",'id':order_id },
                    success: function (data) {
                        if (data.status ) {  console.log(data);
                            var div = $('.order'+data.id);
                            div.removeClass('bg-danger');
                            div.addClass('bg-success');
                            div.text('received')
                            $('.aalert').removeClass('alert-danger');
                            $('.aalert').addClass('alert-success');
                            $('.aalert').text('updated succeeded');
                            $('.aalert').show().delay(5000).fadeOut();

                        }
                    }, error: function (reject) { console.log(reject);
                        $('.aalert').removeClass('alert-success');
                        $('.aalert').addClass('alert-danger');
                        $('.aalert').text('update didnt succeed');
                        $('.aalert').show().delay(5000).fadeOut();
                    }
                });

            }




        });


    </script>

@endsection
