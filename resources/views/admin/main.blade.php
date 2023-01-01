@extends('dashboard_admin_layout.dashboard')

@section('content')

    <div class="container-fluid">
        <div class="alert  aalert" style="display: none;" role="alert"></div>
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        </div>

        <div class="row">

            <div class="col-lg-12">

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">send email</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <form class="mail_form" >
                                @csrf
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="projectinput1"> type </label>
                                    <select name="mail_type"  class="custom-select custom-select-sm form-control form-control-sm">
                                        <option value="all">all</option>
                                        <option value="users">users</option>
                                        <option value="admins">admins</option>
                                        <option value="sellers">sellers</option>
                                        <option value="visitors">visitors</option>
                                    </select>
                                    @error('mail_type')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <textarea name="email_text" cols="50" rows="10" placeholder="text ..."></textarea>
                                </div>
                                <button  id="send_email" class="btn btn-success btn-icon-split send_email">
                                    <span class="text">send email</span>
                                </button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>



        </div>

    </div>

@endsection

@section('ajax')
    <script>

        $(document).on('click', '#send_email', function (e) {

            e.preventDefault();
            var formData = new FormData($('.mail_form')[0]);

            console.log("gg");
            $.ajax({
                type: 'post',
                url: "{{ route('SendEmail') }}",
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
                success: function (data) {
                    if (data.status == true) {
                        console.log(data);
                        $('.aalert').removeClass('alert-danger');
                        $('.aalert').addClass('alert-success');
                        $('.aalert').text('email sent');
                        $('.aalert').show().delay(5000).fadeOut();
                    }
                }, error: function (reject) {

                    console.log(reject);
                    $('.aalert').removeClass('alert-success');
                    $('.aalert').addClass('alert-danger');
                    $('.aalert').text('email error');
                    $('.aalert').show().delay(5000).fadeOut();

                }
            });
        });
    </script>
@endsection
