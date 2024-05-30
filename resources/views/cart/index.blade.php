@include('navbar')



    <div class="container">
        <h1 class="my-4">Shopping Cart</h1>
        
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        
        @if($cartItems->isEmpty())
            <div class="alert alert-info">Your cart is empty.</div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Product</th>
                            <th scope="col">Image</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Actions</th>
                            <th scope="col">Price</th>
                            <th scope="col">Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $grandTotal = 0; // Initialize grand total
                        @endphp
                        @foreach($cartItems as $cartItem)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $cartItem->product->title }}</td>
                                <td><img src="{{ asset($cartItem->product->image) }}" alt="Product Image" class="img-thumbnail" style="width: 100px; height: auto;"></td>
                                <td>{{ $cartItem->quantity }}</td>
                                <td>
                                    <form action="{{ route('cart.delete', $cartItem->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                                    </form>
                                </td>
                                <td>₹{{ number_format($cartItem->product->price, 2) }}</td>
                                <td>₹{{ number_format($cartItem->quantity * $cartItem->product->price, 2) }}</td>
                                @php
                                    $grandTotal += $cartItem->quantity * $cartItem->product->price; // Calculate grand total
                                @endphp
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="5" class="text-right"><strong>Grand Total:</strong></td>
                            <td>₹{{ number_format($grandTotal, 2) }}</td>
                            <td>
                                <form action="{{ route('cart.purchase', $cartItem->id) }}" method="POST" class="d-inline-block">
                                    @csrf
                                    <script src="https://checkout.razorpay.com/v1/checkout.js"
                                            data-key="{{ config('razorpay.key') }}"
                                            data-amount="{{ $grandTotal * 100 }}"
                                            data-currency="INR"
                                            data-buttontext="Buy Now"
                                            data-name="Test Payment"
                                            data-description="Payment"
                                            data-prefill.name="{{ $user->name }}"
                                            data-prefill.email="user@gmail.com"
                                            data-theme.color="#ff7529">
                                    </script>
                                </form>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div id="shippingFormContainer" class="my-4">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h2>Shipping Information</h2>
                            </div>
                            <div class="card-body">
                                <form id="shippingForm" method="POST" action="{{ route('shipping.store') }}">
                                    @csrf

                                    <div class="form-group">
                                        <label for="full_name">Full Name</label>
                                        <input type="text" name="full_name" class="form-control" id="full_name" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="address_line_1">Address Line 1</label>
                                        <input type="text" name="address_line_1" class="form-control" id="address_line_1" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="city">City</label>
                                        <input type="text" name="city" class="form-control" id="city">
                                    </div>

                                    <div class="form-group">
                                        <label for="postal_code">Postal Code</label>
                                        <input type="text" name="postal_code" class="form-control" id="postal_code">
                                    </div>

                                    <div class="form-group">
                                        <label for="phone_number">Phone Number</label>
                                        <input type="text" name="phone_number" class="form-control" id="phone_number">
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-block mt-3">Submit</button>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="shippingInfo" class="my-4" style="display: none;"></div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const shippingFormContainer = document.getElementById('shippingFormContainer');
            const shippingForm = document.getElementById('shippingForm');
            const shippingInfo = document.getElementById('shippingInfo');

            shippingForm.addEventListener('submit', function(event) {
                event.preventDefault(); // Prevent default form submission

                // Perform AJAX request to submit form data
                fetch(shippingForm.action, {
                    method: 'POST',
                    body: new FormData(shippingForm),
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.errors) {
                        // Handle validation errors
                        console.error(data.errors);
                        return;
                    }

                    // Hide the form
                    shippingFormContainer.style.display = 'none';
                    // Display the shipping information
                    shippingInfo.innerHTML = `
                        <div class="alert alert-info">
                            <p><strong>Full Name:</strong> ${data.full_name}</p>
                            <p><strong>Address:</strong> ${data.address_line_1}, ${data.city}, ${data.postal_code}</p>
                            <p><strong>Phone Number:</strong> ${data.phone_number}</p>
                        </div>
                    `;
                    shippingInfo.style.display = 'block'; // Show the shipping information section

                    // Enable Razorpay button after shipping info is submitted
                    document.getElementById('razorpayButton').style.display = 'block';
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        });
    </script>

    <style>
        .table td, .table th {
            vertical-align: middle;
        }
        .razorpay-payment-button {
            background-color:#F4CE14 !important;
            color: white !important;
            border: none !important;
            padding: 10px 20px !important;
            font-size: 16px !important;
            border-radius: 5px !important;
            cursor: pointer !important;
        }
    </style>

