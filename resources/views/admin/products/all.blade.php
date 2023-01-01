@extends('dashboard_admin_layout.dashboard')

@section('content')

    <div class="container-fluid">
        <!-- DataTales Example -->

        <div class="alert  aalert" style="display: none; " role="alert"></div>



        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
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
                                            Name
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                            colspan="1" aria-label="Position: activate to sort column ascending"
                                            style="width: 150px;">title
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                            colspan="1" aria-label="Salary: activate to sort column ascending"
                                            style="width: 120px;">seller
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                            colspan="1" aria-label="Salary: activate to sort column ascending"
                                            style="width: 120px;">quantity
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                            colspan="1" aria-label="Salary: activate to sort column ascending"
                                            style="width: 120px;">price
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                            colspan="1" aria-label="Salary: activate to sort column ascending"
                                            style="width: 120px;">photo
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                            colspan="1" aria-label="Office: activate to sort column ascending"
                                            style="width: 150px;">category
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                            colspan="1" aria-label="Office: activate to sort column ascending"
                                            style="width: 150px;">currencie
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                            colspan="1" aria-label="Office: activate to sort column ascending"
                                            style="width: 150px;">status
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                            colspan="1" aria-label="Age: activate to sort column ascending"
                                            style="width: 150px;">creted at
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                            colspan="1" aria-label="Start date: activate to sort column ascending"
                                            style="width: 150px;">last update
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                            colspan="1" aria-label="Salary: activate to sort column ascending"
                                            style="width: 120px;">oprations
                                        </th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($products as $product)
                                        <tr class="odd product{{ $product ->id }}"  >
                                            <td class="sorting_1">{{ $product->name }}</td>
                                            <td>{{ $product->title }}</td>
                                            <td>{{ $product->seller->name }}</td>
                                            <td>{{ $product->quantity }}</td>
                                            <td>{{ $product->price }}</td>
                                            @if($product->image)
                                            <td><img src="{{ \Illuminate\Support\Facades\Storage::disk('Images')->url('/'.$product->photo)  }}" width="50px" height="50px"  > </td>
                                            @else
                                                <td>no image</td>
                                            @endif
                                            <td>{{  $product->category->name }}</td>
                                            <td>{{ $product->currencie->cc }}</td>
                                            @if($product->status == '1')
                                                <td>
                                                    <div class="col-lg-6 mb-4">
                                                        <div class="card bg-success text-white shadow"
                                                             style="text-align: center;width: 80px;">
                                                            active
                                                        </div>
                                                    </div>
                                                </td>
                                            @else
                                                <td>
                                                    <div class="col-lg-6 mb-4">
                                                        <div class="card bg-danger text-white shadow"
                                                             style="text-align: center;width: 80px;">
                                                            not active
                                                        </div>
                                                    </div>
                                                </td>
                                            @endif
                                            <td>{{ $product->created_at }}</td>
                                            <td>{{ $product->updated_at }}</td>
                                            <td>
                                                <a  product_id="{{ $product->id }}"  class=" delete_product btn btn-danger btn-circle btn-lg ">
                                                    <i class="fas fa-trash "></i>
                                                </a>
                                                <a href="{{ route('admin.products.update',['id'=>$product->id]) }}"
                                                   class="btn btn-warning btn-circle btn-lg">
                                                    <i class="fa fa-wrench" aria-hidden="true"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="justify-content-center d-flex">

                                </div>
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


        $(document).on('click', '.delete_product', function (e) {

            var produnt_id =  $(this).attr('product_id');

            $.ajax({
                type: 'post',
                url: "{{ route('admin.product.delete') }}",
                data: {'_token': "{{csrf_token()}}",'id':produnt_id},
                success: function (data) {
                    if (data.status ) {
                        $('.product'+data.id).remove();
                        $('.aalert').removeClass('alert-danger');
                        $('.aalert').addClass('alert-success');
                        $('.aalert').text('deleted succeeded');
                        $('.aalert').show();

                    }
                }, error: function (reject) { console.log(reject);
                    $('.aalert').removeClass('alert-success');
                    $('.aalert').addClass('alert-danger');
                    $('.aalert').text('delete didnt succeed');
                    $('.aalert').show();
                }
            });
        });

    </script>

@endsection
