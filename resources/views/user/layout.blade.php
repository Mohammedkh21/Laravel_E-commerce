<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>


    <!-- Custom fonts for this template-->
    <link href="{{ \Illuminate\Support\Facades\URL::asset('asset/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ \Illuminate\Support\Facades\URL::asset('asset/css/sb-admin-2.min.css') }}" rel="stylesheet">

</head>

<body id="page-top">
<!-- Page Wrapper -->
<div id="wrapper">

    <!-- Sidebar -->

    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                @yield('nav')
                <div class="navbar-collapse collapse w-100 order-1 order-md-0 dual-collapse2">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Categorys
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('allCategory') }}">all</a>
                                @foreach($categorys as $category)
                                    <a class="dropdown-item" href="{{ route('customCategory',['name'=>$category->name]) }}">{{ $category->name }}</a>
                                @endforeach
                            </div>
                        </li>
                        @auth()
                            <li class="nav-item ">
                                <a class="nav-link " href="{{ route('myOrders') }}"  >
                                    my Orders
                                </a>
                            </li>
                        @endauth
                    </ul>
                </div>





                <!-- Topbar Navbar -->
                <ul class="navbar-nav ml-auto">

                    <!-- Nav Item - Search Dropdown (Visible Only XS) -->




                    @yield('cart')
                    <!-- Nav Item - cart -->
                    <li class="nav-item dropdown no-arrow mx-1" >
                        <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <svg id="b" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-basket3-fill" viewBox="0 0 16 16">
                                <path d="M5.757 1.071a.5.5 0 0 1 .172.686L3.383 6h9.234L10.07 1.757a.5.5 0 1 1 .858-.514L13.783 6H15.5a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H.5a.5.5 0 0 1-.5-.5v-1A.5.5 0 0 1 .5 6h1.717L5.07 1.243a.5.5 0 0 1 .686-.172zM2.468 15.426.943 9h14.114l-1.525 6.426a.75.75 0 0 1-.729.574H3.197a.75.75 0 0 1-.73-.574z"></path>
                            </svg>
                            <!-- Counter - Alerts
                            <span class="badge badge-danger badge-counter">3+</span>  -->
                        </a>
                        <!-- Dropdown - Alerts -->
                        <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in
             aria-labelledby">
                            <h6 class="dropdown-header">
                                products
                            </h6>
                            <ul class="h-250px overflow-auto c-scrollbar-light list-group list-group-flush">

                                <li class="list-group-item" >
                                    <div id="cart1">
                                        @if(isset($CartProduct))

                                            @foreach($CartProduct as $product)
                                                <span class="d-flex align-items-center product{{ $product->id  }}">
                                <img width="60px" height="60px" src="{{ asset('storage/images/'.$product->photo) }}"  class="img-fit size-60px rounded lazyloaded" >
                                <span class="minw-0 pl-2 flex-grow-1">
                                    <span class="fw-600 mb-1 text-truncate-2">
                                            {{ $product->name  }}
                                    </span>
                                    <span class="">{{ $product->quantity }}x</span>
                                    <span class="">{{ $product->currencie->symbol .' '.  $product->price }}</span>
                                </span>
                                <button product_id='{{ $product->id  }}'   class=' RemoveFromCart btn btn-sm btn-icon stop-propagation'>
                                    <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash3-fill' viewBox='0 0 16 16'>
                                      <path d='M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5Zm-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5ZM4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06Zm6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528ZM8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5Z'/>
                                    </svg>
                                </button>
                            </span>
                                            @endforeach
                                        @endif
                                    </div>
                                    <a href="{{ route('checkoutPage') }}" id="checkout" class=" dropdown-item text-center btn btn-success small text-gray-500" >checkout : ${{ $total }}</a>
                                </li>
                            </ul>

                            <!-- <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>  -->

                    </li>

                    @auth
                    <li class="nav-item dropdown no-arrow mx-1">
                        <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-bell fa-fw"></i>
                            <!-- Counter - Alerts -->
                            <span class="badge badge-danger badge-counter">{{ auth()->user()->unreadNotifications->count()}}</span>
                        </a>
                        <!-- Dropdown - Alerts -->
                        <div style="max-height: 300px;overflow: auto;" class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
                            <h6 class="dropdown-header">
                                Notification
                            </h6>
                            @foreach( auth()->user()->Notifications as $notification)
                            <a class="dropdown-item d-flex align-items-center notifications" notification_id="{{ $notification->id }}"   href="{{ route('myOrders') }}"
                               @if($notification->read_at) style="background-color: #ebebeb" @endif >
                                <div class="mr-3">
                                    <div class="icon-circle bg-primary">
                                        <i class="fas fa-file-alt text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="small text-gray-500">{{ $notification->created_at }}</div>
                                    <span class="font-weight-bold">{{ $notification->data['message'] }}</span>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </li>
                    @endauth
                    <div class="topbar-divider d-none d-sm-block"></div>
                    @guest
                        <li class="nav-item dropdown no-arrow mx-1" style="margin-top: 20px" >
                            <a href="{{ route('login') }}">login</a>
                        </li>
                    @endguest
                    @auth
                        <li class="nav-item dropdown no-arrow mx-1" style="margin-top: 20px" >
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="link-primary" style="border: 0px;background: none"  type="submit">logout</button>
                            </form>

                        </li>
                    @endauth

                    <!-- Nav Item - Alerts -->


                    <!-- Nav Item - Messages -->




                    <!-- Nav Item - User Information -->


                </ul>

            </nav>
            <!-- End of Topbar -->

            <!-- Begin Page Content -->
            @yield('content')

            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->
        <di id="MailAlart">

        </di>
        <!-- Footer -->
        <footer class="sticky-footer bg-white" >
            <di class="alert  " id="MailAlart">

            </di>
            <div id="ddv4d" class="mt-3">
                <button id="sendMail" type="button" class="btn btn-danger butt">الاشتراك</button>
                <input id="inputMail" class="inp" type="email" placeholder="عنوان بريدك الالكتروني">
            </div>
            <br><br><br><br>
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Copyright &copy; Your Website 2021</span>
                </div>
            </div>
        </footer>
        <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="login.html">Logout</a>
            </div>
        </div>
    </div>
</div>
    @yield('ajax')
<!-- Bootstrap core JavaScript-->
<script>
    $(document).on('click', '.notifications', function (e) {
        var notification_id =  $(this).attr('notification_id');
        $.ajax({
            type: 'post',
            url: "{{ route('MakeNotificationAsReaded') }}",
            data: {'_token': "{{csrf_token()}}",'notification_id':notification_id},
        });
    });
    $(document).on('click', '#sendMail', function (e) {

        var email =  $('#inputMail').val();

        $.ajax({
            type: 'post',
            url: "{{ route('StoreMail') }}",
            data: {'_token': "{{csrf_token()}}",'email':email},
            success: function (data) {
                if (data.status ) {

                    $('#MailAlart').removeClass('alert-danger');
                    $('#MailAlart').addClass('alert-success');
                    $('#MailAlart').text('succeeded');
                    $('#MailAlart').show().delay(5000).fadeOut();

                }
            }, error: function (reject) {console.log(reject);
                $('#MailAlart').removeClass('alert-success');
                $('#MailAlart').addClass('alert-danger');
                $('#MailAlart').text('email exist');
                $('#MailAlart').show().delay(5000).fadeOut();
            }
        });
    });
</script>
<script src="{{ asset('asset/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('asset/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- Core plugin JavaScript-->
<script src="{{ asset('asset/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

<!-- Custom scripts for all pages-->
<script src="{{ asset('asset/js/sb-admin-2.min.js') }}"></script>

<!-- Page level plugins -->
<script src="{{ asset('asset/vendor/chart.js/Chart.min.js') }}"></script>

<!-- Page level custom scripts -->
<script src="{{ asset('asset/js/demo/chart-area-demo.js') }}"></script>
<script src="{{ asset('asset/js/demo/chart-pie-demo.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>

</body>
<style>
    #MailAlart{
        width: 400px;
        height: 40px;
        /* color: black; */
        position: relative;
        left: 20px;
        z-index: 5;
        left: 637px;
        top: 40px;
    }
    #ddv4d{
        width: 75%;
        float: right;
        position: relative;
        width: 400px;
        background-color: black;
        right: 650px;
    }
    #ddv4d input{
        width: 100%;
        float: right;
        text-align: right;
        height: 44px;
        border-radius: 5px;
        padding: 10px;
        border: none;
        outline:none;
    }
    #ddv4d input:active{
        border: none;
        outline:none;
    }
    #ddv4d button{
        position: absolute;
        width: 87px;
        float: left;
        left: 5px;
        top: 5px;
        font-size: 14px;
    }
</style>

</html>
