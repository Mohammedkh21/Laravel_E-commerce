@extends('dashboard_admin_layout.dashboard')

@section('content')

    <div class="app-content content">
        <div class="content-wrapper">

            <div class="content-body">
                <!-- Basic form layout section start -->
                <section id="basic-form-layouts">
                    <div class="row match-height">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title" id="basic-layout-form"> add new sellers </h4>
                                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                            <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                            <li><a data-action="close"><i class="ft-x"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-content collapse show">
                                    <div class="card-body">
                                        <form class="form" action="{{ route('admin.product.update') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $product->id }}  ">
                                            <div class="form-body">
                                                <h4 class="form-section"><i class="ft-home"></i> info </h4>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="projectinput1"> name </label>
                                                            <input type="text" value="{{ $product->name }}" id="name" class="form-control" placeholder="name" name="name">
                                                            @error('name')
                                                            <small class="text-danger">{{ $message }}</small>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="projectinput1"> title  </label>
                                                            <input type="text" value="{{ $product->title }}" id="title" class="form-control" placeholder="title " name="title">
                                                            @error('title')
                                                            <small class="text-danger">{{ $message }}</small>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="projectinput1"> quantity </label>
                                                            <input type="number" value="{{ $product->quantity }}" id="quantity" class="form-control" placeholder="quantity" name="quantity">
                                                            @error('quantity')
                                                            <small class="text-danger">{{ $message }}</small>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="projectinput1"> price </label>
                                                            <div class="input-group mb-3">
                                                                <select name="currencie_id" class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Dropdown
                                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                                        @foreach($currencies as $currencie)
                                                                            <option @if($currencie->id == $product->currencie_id ) selected  @endif value="{{ $currencie->id }}" >{{ $currencie->cc }}</option>
                                                                        @endforeach

                                                                    </ul>
                                                                </select >
                                                                <input name="price" value="{{ $product->price }}" type="number" class="form-control" aria-label="Text input with dropdown button">
                                                            </div>
                                                            @error('currencie_id')
                                                            <small class="text-danger">{{ $message }}</small>
                                                            @enderror
                                                            @error('price')
                                                            <small class="text-danger">{{ $message }}</small>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="projectinput1"> seller </label>
                                                            <select name="seller_id"  class="custom-select custom-select-sm form-control form-control-sm">
                                                                @foreach($sellers as $seller)
                                                                    <option @if($product->seller_id == $seller->id) selected @endif  value="{{ $seller->id }}">{{ $seller->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('seller_id')
                                                            <small class="text-danger">{{ $message }}</small>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="projectinput1"> seller </label>
                                                            <select name="category_id"  class="custom-select custom-select-sm form-control form-control-sm">
                                                                @foreach($categorys as $category)
                                                                    <option @if($product->category_id == $category->id) selected @endif  value="{{ $category->id }}">{{ $category->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('category_id')
                                                            <small class="text-danger">{{ $message }}</small>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="projectinput1"> photo  </label>
                                                            <input type="file" class="form-control" placeholder="photo " name="photo">
                                                            @error('photo')
                                                            <small class="text-danger">{{ $message }}</small>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group mt-1">
                                                            <input type="checkbox" @if($product->status) checked @endif  value="1" name="status" id="switcheryColor4" class="switchery" data-color="success" data-switchery="true" >
                                                            <span class="switchery switchery-default" style="background-color: rgb(55, 188, 155); border-color: rgb(55, 188, 155); box-shadow: rgb(55, 188, 155) 0px 0px 0px 16px inset; transition: border 0.4s ease 0s, box-shadow 0.4s ease 0s, background-color 1.2s ease 0s;">
                                                                <small style="left: 20px; background-color: rgb(255, 255, 255); transition: background-color 0.4s ease 0s, left 0.2s ease 0s;">
                                                                </small>
                                                            </span>
                                                            <label for="switcheryColor4" class="card-title ml-1">status </label>

                                                        </div>
                                                    </div>
                                                </div>

                                            </div>


                                            <div class="form-actions">
                                                <button type="button" class="btn btn-warning mr-1" onclick="history.back();">
                                                    <i class="ft-x"></i> cansel
                                                </button>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="la la-check-square-o"></i> update
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- // Basic form layout section end -->
            </div>
        </div>
    </div>

@endsection
