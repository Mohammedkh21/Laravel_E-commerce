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
                                            style="width: 150px;">cc
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                            colspan="1" aria-label="Salary: activate to sort column ascending"
                                            style="width: 120px;">symbol
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                            colspan="1" aria-label="Salary: activate to sort column ascending"
                                            style="width: 120px;">status
                                        </th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($Currencies as $Currencie)
                                        <tr class="odd ">
                                            <td class="sorting_1">{{ $Currencie->name }}</td>
                                            <td>{{ $Currencie->cc }}</td>
                                            <td>{{ $Currencie->symbol }}</td>
                                            <td>
                                                <div class="form-check form-switch"
                                                     style=" text-align: center;font-size: 22px;">
                                                    <input Currencie_id="{{ $Currencie->id }}"
                                                           @if($Currencie->status ) checked
                                                           @endif class="form-check-input checkbox-ajax" type="checkbox"
                                                           role="switch" id="flexSwitchCheckDefault">
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 col-md-7">
                                    <div class="dataTables_paginate paging_simple_numbers" id="dataTable_paginate">

                                            {{  $Currencies->links("pagination::bootstrap-4") }}

                                    </div>
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


        $(document).on('click', '.checkbox-ajax', function (e) {

            var Currencie_id = $(this).attr('Currencie_id');

            var checked = $(this).is(':checked');
            var status = checked ? 1 : 0;


            $.ajax({
                type: 'post',
                url: "{{ route('admin.currencies.update') }}",
                data: {'_token': "{{csrf_token()}}", 'id': Currencie_id, 'status': status},
                success: function (data) {
                    if (data.status) {
                        $('.aalert').removeClass('alert-danger');
                        $('.aalert').addClass('alert-success');
                        $('.aalert').text('updated succeeded');
                        $('.aalert').show().delay(5000).fadeOut();

                    }
                }, error: function (reject) {
                    $('.aalert').removeClass('alert-success');
                    $('.aalert').addClass('alert-danger');
                    $('.aalert').text('update didnt succeed');
                    $('.aalert').show().delay(5000).fadeOut();
                }
            });

        });


    </script>

@endsection
