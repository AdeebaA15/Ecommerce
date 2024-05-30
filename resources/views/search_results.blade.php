@extends('layouts.app')
@section('content')
    <div class="container">
        <h2 class="my-4 text-center">Search Results</h2>
        @if($products->isEmpty())
            <div class="alert alert-warning" role="alert">
                No products found.
            </div>
        @else
            <div class="row">
                @foreach($products as $product)
                    <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="imageholder">
                                <a href="{{ route('product.details', ['id' => $product->id]) }}">
                                    <img src="{{ asset($product->image) }}" class="card-img-top img-fluid" alt="{{ $product->title }}">
                                </a>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">
                                    <a href="{{ route('product.details', ['id' => $product->id]) }}" class="text-decoration-none text-dark">{{ $product->title }}</a>
                                </h5>
                                <p class="card-text text-muted"><strong>Price: ₹{{ $product->price }}</strong></p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <style>
        .card:hover {
            transform: translateY(-10px);
            transition: transform 0.2s;
        }
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
        .imageholder {
            overflow: hidden;
        }
    </style>
@endsection
