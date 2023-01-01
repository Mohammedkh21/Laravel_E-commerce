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
                                            style="width: 150px;">email
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                            colspan="1" aria-label="Position: activate to sort column ascending"
                                            style="width: 150px;">products number
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
                                    @foreach($sellers as $seller)
                                        <tr class="odd seller{{ $seller ->id }}"  >
                                            <td class="sorting_1">{{ $seller->name }}</td>
                                            <td>{{ $seller->email }}</td>
                                            <td>{{ count($seller->product) }}</td>
                                            @if($seller->status == '1')
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
                                            <td>{{ $seller->created_at }}</td>
                                            <td>{{ $seller->updated_at }}</td>
                                            <td>
                                                @can( 'deleteSeller' , auth()->guard('admin')->user() )
                                                <a  seller_id="{{ $seller->id }}"  class=" delete_seller btn btn-danger btn-circle btn-lg ">
                                                    <i class="fas fa-trash "></i>
                                                </a>
                                                @endcan
                                                @can( 'updateSeller' , auth()->guard('admin')->user() )
                                                <a href="{{ route('admin.sellers.update',['id'=>$seller->id]) }}"
                                                   class="btn btn-warning btn-circle btn-lg">
                                                    <i class="fa fa-wrench" aria-hidden="true"></i>
                                                </a>
                                                @endcan
                                            </td>
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


        $(document).on('click', '.delete_seller', function (e) {

            var seller_id =  $(this).attr('seller_id');

            $.ajax({
                type: 'post',
                url: "{{ route('admin.seller.delete') }}",
                data: {'_token': "{{csrf_token()}}",'id':seller_id},
                success: function (data) {
                    if (data.status ) {
                        $('.seller'+data.id).remove();
                        $('.aalert').removeClass('alert-danger');
                        $('.aalert').addClass('alert-success');
                        $('.aalert').text('deleted succeeded');
                        $('.aalert').show();

                    }
                }, error: function (reject) {
                    $('.aalert').removeClass('alert-success');
                    $('.aalert').addClass('alert-danger');
                    $('.aalert').text('delete didnt succeed');
                    $('.aalert').show();
                }
            });
        });

    </script>

@endsection
