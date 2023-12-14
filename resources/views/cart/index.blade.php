@extends('layouts.customer')

@section('content')
    <div class="container">
        <h2>Your Cart</h2>

        <!-- Display success and error messages -->
        <div id="success-message" class="alert alert-success d-none"></div>
        <div id="error-message" class="alert alert-danger d-none"></div>

        @if ($cartItems->isEmpty())
            <p>Your cart is empty.</p>
        @else
            <table class="table" id="cart-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cartItems as $cartItem)
                        <tr>
                            <td>{{ $cartItem->product->title }}</td>
                            <td>${{ $cartItem->product->price }}</td>
                            <td>
                                <input type="number" class="form-control" value="{{ $cartItem->quantity }}"
                                    id="quantity-{{ $cartItem->id }}" min="1">
                            </td>
                            <td>${{ $cartItem->product->price * $cartItem->quantity }}</td>
                            <td>
                                <button class="btn btn-info btn-sm"
                                    onclick="updateQuantity({{ $cartItem->id }})">Update</button>
                                <button class="btn btn-danger btn-sm"
                                    onclick="removeFromCart({{ $cartItem->id }})">Remove</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <p>Total: ${{ $total }}</p>
            <button class="btn btn-primary" onclick="checkout()">Checkout</button>
        @endif
    </div>

    <script>
        function updateQuantity(cartItemId) { 
            var quantity = $('#quantity-' + cartItemId).val();
            
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            var url = "{{ route('customer.cart.update', ":cartItemId") }}";
            url = url.replace(':cartItemId', cartItemId);

            $.ajax({
                type: 'PUT',
                url: url,
                 data: {
                    quantity: quantity,
                    // Include the CSRF token in the request data
                    _token: csrfToken
                },
                success: function(response) {
                    // Show success message
                    $('#success-message').text('Quantity updated successfully').removeClass('d-none');

                    // Update the total and reload the page after a brief delay
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                },
                error: function(error) {
                    // Show error message
                    $('#error-message').text('Error updating quantity').removeClass('d-none');
                }
            });
        }

        function removeFromCart(cartItemId) {
            if (confirm("Are you sure you want to remove this item from your cart?")) {
                var url = "{{ route('customer.cart.remove', ":cartItemId") }}";
                url = url.replace(':cartItemId', cartItemId);

                var csrfToken = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    type: 'DELETE',
                    url: url,
                    data: {
                        _token: csrfToken
                    },
                    success: function(response) {
                        // Show success message
                        $('#success-message').text('Item removed from cart successfully').removeClass('d-none');

                        // Reload the page after a brief delay
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    },
                    error: function(error) {
                        // Show error message
                        $('#error-message').text('Error removing item from cart').removeClass('d-none');
                    }
                });
            }
        }

        function checkout() {
            alert('Checkout button clicked!.');
        }
    </script>
@endsection
