

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Your Orders</h1>

        @if ($orders && !$orders->isEmpty())
            <div class="list-group">
                @foreach ($orders as $order)
                    <div class="list-group-item">
                        <h5 class="mb-1">Order ID: {{ $order->id }}</h5>
                        <p class="mb-1">Order Date: {{ $order->created_at->format('F j, Y') }}</p>
                        <ul class="list-group list-group-flush">
                            @if ($order->products && !$order->products->isEmpty())
                                @foreach ($order->products as $product)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <img src="{{ asset($product->image) }}" alt="{{ $product->title }}" style="max-width: 100px; max-height: 100px;">
                                            <span class="font-weight-bold">{{ $product->title }}</span>
                                        </div>
                                        <div>
                                            <span class="badge badge-primary">Quantity: {{ $product->pivot->quantity }}</span>
                                            <span class="badge badge-success">Price: {{ $product->pivot->price }}</span>
                                        </div>
                                    </li>
                                @endforeach
                            @else
                                <li class="list-group-item">No products found for this order</li>
                            @endif
                        </ul>
                        <p class="mt-2"><strong>Total Price:</strong> {{ $order->total_price }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <p>No orders found.</p>
        @endif
    </div>
@endsection