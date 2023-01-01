@extends('user.layout')


@section('cart')


    <!-- Nav Item - cart -->
    <li class="nav-item dropdown no-arrow mx-1" >


    </li>


@endsection

@section('content')

    <div class="container-fluid "  >




        <div class="row" style="padding: 0px; text-align: center">
            <div id="" style=" text-align: center">
                @if(isset($CartProduct))

                    @foreach($CartProduct as $product)
                        <span class="d-flex align-items-center product{{ $product->id  }}">
                                <span class="minw-0 pl-2 flex-grow-1">
                                    <img width="60px" height="60px" src="{{ asset('storage/images/'.$product->photo) }}"  class="img-fit size-60px rounded lazyloaded" >
                                    <span class="fw-600 mb-1 text-truncate-2">
                                            {{ $product->name  }}
                                    </span>
                                    <span class="">{{ $product->quantity }}x</span>
                                    <span class="">{{ $product->currencie->symbol .' '.  $product->price }}</span>
                                </span>

                            </span>
                        <hr>
                    @endforeach
                @endif
            </div>
            <form action="{{ route('checkout') }}" method="POST" class="container mt-5">
                @csrf
                <label> address
                    <input name="address" type="text">
                </label>
                @error('address')
                <small class="text-danger">{{ $message }}</small>
                @enderror

                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="pay_method" value="pay_when_received" id="inlineCheckbox1" value="option1">
                        <label class="form-check-label" for="inlineCheckbox1">pay when received</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="pay_method" value="pay_when_received" id="inlineCheckbox2" value="option2">
                        <label class="form-check-label" for="inlineCheckbox2">pay with paypal</label>
                    </div>
                    @error('pay_method')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <hr>
                <label>Total : ${{ $total }}</label>
                <br>
                <button type="submit" class="btn bg-success"> chckout</button>
            </form>

        </div>


    </div>

@endsection



@section('ajax')



@endsection
