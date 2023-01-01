@extends('dashboard_admin_layout.dashboard')

@section('content')

    <div class="container-fluid">
        <!-- DataTales Example -->

        <div class="alert  aalert" style="display: none; " role="alert"></div>

        @if(\Illuminate\Support\Facades\Session::has('update'))
            <div class="alert  alert-success ualart" style="display: none; " role="alert">{{ \Illuminate\Support\Facades\Session::get('update') }}</div>
            <script>
                $('.ualart').show().delay(5000).fadeOut();
            </script>
        @endif

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
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($categorys as $category)
                                        <tr class="odd category{{ $category ->id }}"  >
                                            <td class="sorting_1">{{ $category->name }}</td>
                                            @if($category->status == '1')
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
                                            <td>{{ $category->created_at }}</td>
                                            <td>{{ $category->updated_at }}</td>
                                            <td>
                                                @can( 'deleteCategory' , auth()->guard('admin')->user() )
                                                <a  category_id="{{ $category->id }}"  class=" category_admin btn btn-danger btn-circle btn-lg ">
                                                    <i class="fas fa-trash "></i>
                                                </a>
                                                @endcan
                                                @can( 'updateCategory' , auth()->guard('admin')->user() )
                                                <a href="{{ route('admin.categorys.update',['id'=>$category->id]) }}"
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


        $(document).on('click', '.category_admin', function (e) {

            var category_id =  $(this).attr('category_id');

            $.ajax({
                type: 'post',
                url: "{{ route('admin.category.delete') }}",
                data: {'_token': "{{csrf_token()}}",'id':category_id},
                success: function (data) {
                    if (data.status ) {
                        $('.category'+data.id).remove();
                        $('.aalert').removeClass('alert-danger');
                        $('.aalert').addClass('alert-success');
                        $('.aalert').text('deleted succeeded');
                        $('.aalert').show().delay(5000).fadeOut();

                    }
                }, error: function (reject) {
                    $('.aalert').removeClass('alert-success');
                    $('.aalert').addClass('alert-danger');
                    $('.aalert').text('delete didnt succeed');
                    $('.aalert').show().delay(5000).fadeOut();
                }
            });
        });

    </script>

@endsection
