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
                <div class="table-responsive" style="height: 800px">
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
                                            style="width: 150px;">permissions
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
                                    @foreach($admins as $admin)
                                        <tr class="odd seller{{ $admin ->id }}"  >
                                            <td class="sorting_1">{{ $admin->name }}</td>
                                            <td>{{ $admin->email }}</td>
                                            <td>
                                                <ul class="navbar-nav mr-auto">
                                                    <li class="nav-item dropdown">
                                                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown{{ $admin->id }}" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            permissions
                                                        </a>
                                                        <div style="text-align: center" class="dropdown-menu" aria-labelledby="navbarDropdown{{ $admin->id }}">
                                                            @foreach($admin->permissions as $permission)
                                                                <span  > {{ reset($permission) }}  </span> <br>
                                                                <span class="dropdown-item  align-items-center" >
                                                                    <span style="float: left">view</span>
                                                                    <span style="float:right;" class=" form-switch">
                                                                        <input admin_id="{{ $admin->id }}" opartion="v" category="{{ reset($permission) }}"  @if($permission->v) checked @endif  class="form-check-input checkbox-ajax" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                                                    </span>
                                                                    <br>
                                                                    <span style="float: left">add</span>
                                                                    <span style="float:right;" class=" form-switch">
                                                                        <input admin_id="{{ $admin->id }}" opartion="a"  category="{{ reset($permission) }}" @if($permission->a) checked @endif  class="form-check-input checkbox-ajax" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                                                    </span>
                                                                    <br>
                                                                    <span style="float: left">update</span>
                                                                    <span style="float:right;" class=" form-switch">
                                                                        <input admin_id="{{ $admin->id }}" opartion="u"  category="{{ reset($permission) }}" @if($permission->u) checked @endif  class="form-check-input checkbox-ajax" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                                                    </span>
                                                                    <br>
                                                                    <span style="float: left">delete</span>
                                                                    <span style="float:right;" class=" form-switch">
                                                                        <input admin_id="{{ $admin->id }}"  opartion="d" category="{{ reset($permission) }}"  @if($permission->d) checked @endif  class="form-check-input checkbox-ajax" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                                                    </span>
                                                                    <br>
                                                                </span>
                                                            @endforeach

                                                        </div>
                                                    </li>

                                                </ul>
                                            </td>
                                            <td>{{ $admin->created_at }}</td>
                                            <td>{{ $admin->updated_at }}</td>
                                            <td>
                                                @can('deleteAdmin',auth()->guard('admin')->user())
                                                <a  admin_id="{{ $admin->id }}"  class=" delete_admin btn btn-danger btn-circle btn-lg ">
                                                    <i class="fas fa-trash "></i>
                                                </a>
                                                @endcan
                                                @can('updateAdmin',auth()->guard('admin')->user())
                                                <a href="{{ route('admin.admins.update',['id'=>$admin->id]) }}"
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
        $(document).on('click', '.checkbox-ajax', function (e) {
            var admin_id =  $(this).attr('admin_id');
            var opartion =  $(this).attr('opartion');
            var category =  $(this).attr('category');

            var checked = $(this).is(':checked');
            var status = checked ? 1 :0;

            $.ajax({
                type: 'post',
                url: "{{ route('admin.permissions.update') }}",
                data: {'_token': "{{csrf_token()}}",'id':admin_id ,'opartion' : opartion,'category': category,'status': status},
                success: function (data) {
                    if (data.status ) {
                        $('.aalert').removeClass('alert-danger');
                        $('.aalert').addClass('alert-success');
                        $('.aalert').text('updated succeeded');
                        $('.aalert').show().delay(1000).fadeOut();

                    }
                }, error: function (reject) { console.log(reject);
                    $('.aalert').removeClass('alert-success');
                    $('.aalert').addClass('alert-danger');
                    $('.aalert').text('update didnt succeed');
                    $('.aalert').show().delay(1000).fadeOut();
                }
            });

        });


        $(document).on('click', '.delete_admin', function (e) {

            var admin_id =  $(this).attr('admin_id');

            $.ajax({
                type: 'post',
                url: "{{ route('admin.admin.delete') }}",
                data: {'_token': "{{csrf_token()}}",'id':admin_id},
                success: function (data) {
                    if (data.status ) {
                        $('.seller'+data.id).remove();
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
