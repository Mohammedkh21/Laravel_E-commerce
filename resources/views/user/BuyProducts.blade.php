@extends('user.layout')

@section('nav')

@endsection
@section('cart')




@endsection

@section('content')

    <div class="container-fluid " style="height: 1000px" >

        <div class="alert  aalert" style="display: none;" role="alert"></div>
        <!--
        <div id="cart">
            @if(isset($OrderProducts))
                @foreach($OrderProducts as $product)
                    <div class="card product{{ $product->id  }} " style="width: 10rem;float: right">
                        <img src="{{ asset('storage/images/'.$product->photo) }}" height="100px" width="100px" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name  }}</h5>
                            <p class="card-text">{{ $product->titel }}</p>
                            <p class="card-text">${{ $product->price .' * '. $product->quantity .' = $'.($product->price * $product->quantity)}}</p>
                            <button product_id="{{ $product->id  }}"  class="btn btn-danger RemoveFromCart">remove from cart</button>
                        </div>
                    </div>
                @endforeach
            @endif
            <br><br><br><br><br>
        </div>
        -->


        <div class="row" style="padding: 0px">
        @foreach($products as $product)
            <div class="card" style="width: 18rem;">
                <img src="{{asset('storage/images/'.$product->photo) }}" height="100px" width="100px" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">{{ $product->name  }}</h5>
                    <p class="card-text">{{ $product->titel }}</p>
                    <p class="card-text">{{ $product->currencie->symbol .' '.  $product->price }}</p>
                    <p class="card-text">quantity <input class=" quantity{{ $product->id  }}" value="1" type="number"></p>
                    <button product_id="{{ $product->id  }}"  class="btn btn-primary AddToCart">add to cart</button>
                </div>
            </div>
        @endforeach
        </div>


    </div>

@endsection



@section('ajax')
    <script>

        $(document).on('click', '.AddToCart', function (e) {

            var produnt_id =  $(this).attr('product_id');
            var quantity =  $('.quantity'+produnt_id).val();

            $.ajax({
                type: 'post',
                url: "{{ route('AddProductToCart') }}",
                data: {'_token': "{{csrf_token()}}",'id':produnt_id,'quantity':quantity},
                success: function (data) {
                    if (data.status ) {console.log(data);
                        var total = data.total_price;
                        var data =data.data;
                        var html1 = '';
                        for(var i in data){
                            var p = data[i];

                            html1 +=
                                "<span class='d-flex align-items-center product"+ p.id +"'> "+
                                "<img width='60px' height='60px'src='{{ asset('storage/images/') }}/"+ p.photo +" '   class='img-fit size-60px rounded lazyloaded' >"+
                                "<span class='minw-0 pl-2 flex-grow-1'>"+
                                "<span class='fw-600 mb-1 text-truncate-2'>"+ p.name +"</span>"+
                                "<span >  "+ p.quantity +"x</span>"+
                                "<span >  "+ p.currencie.cc +" "+ p.price +"</span> </span>"+
                                "<button product_id='"+ p.id +"'   class=' RemoveFromCart btn btn-sm btn-icon stop-propagation'> "+
                                       " <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash3-fill' viewBox='0 0 16 16'> "+
                                       " <path d='M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5Zm-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5ZM4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06Zm6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528ZM8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5Z'/>  "+
                                       " </svg>  "+
                                "</button>  "+
                                "</span> "
                            ;

                        }
                        $('#cart1').html(html1);
                        $('#checkout').html("checkout : $"+total);
                        $('.aalert').removeClass('alert-danger');
                        $('.aalert').addClass('alert-success');
                        $('.aalert').text('add product to cart succeeded');
                        $('.aalert').show().delay(5000).fadeOut();

                    }
                }, error: function (reject) {
                    console.log(reject);
                    $('.aalert').removeClass('alert-success');
                    $('.aalert').addClass('alert-danger');
                    $('.aalert').text(reject.responseJSON.message);
                    $('.aalert').show().delay(5000).fadeOut();
                }
            });
        });





        $(document).on('click', '.RemoveFromCart', function (e) {

            var produnt_id =  $(this).attr('product_id');

            $.ajax({
                type: 'post',
                url: "{{ route('DeleteProductFromCart') }}",
                data: {'_token': "{{csrf_token()}}",'id':produnt_id},
                success: function (data) {
                    if (data.status ) {

                        var total = data.total_price;
                        $('.product'+data.id).remove();
                        $('#checkout').html("checkout : $"+total);
                        $('.aalert').removeClass('alert-danger');
                        $('.aalert').addClass('alert-success');
                        $('.aalert').text('remove product from cart succeeded');
                        $('.aalert').show().delay(5000).fadeOut();

                    }
                }, error: function (reject) {console.log(reject);
                    $('.aalert').removeClass('alert-success');
                    $('.aalert').addClass('alert-danger');
                    $('.aalert').text('product not found');
                    $('.aalert').show().delay(5000).fadeOut();
                }
            });
        });

    </script>

@endsection
