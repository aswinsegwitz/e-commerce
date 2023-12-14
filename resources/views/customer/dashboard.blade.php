<!-- resources/views/admin/dashboard.blade.php -->

@extends('layouts.customer')

@section('content')
<div class="container">
    <h2>Products</h2>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-6 g-4">
        @foreach($products as $product)
            <div class="col mb-2">
                <div class="card h-100">
                    <img src="{{ asset('storage/' . $product->main_image) }}" class="card-img-top" alt="{{ $product->title }}">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $product->title }}</h5>
                        <p class="card-text flex-grow-1">{{ $product->description }}</p>
                        <p class="card-text">Price: ${{ $product->price }}</p>
                        <p class="card-text">Stock: {{ $product->stock }}</p>
                        <p class="card-text">Vendor: {{ $product->vendor->name }}</p>

                        <!-- Add to Cart button -->
                        <button class="btn btn-success" onclick="addToCart({{ $product->id }})">Add to Cart</button>
                        <div id="success-message" class="alert alert-success d-none"></div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
    function addToCart(productId) {
        // Send an AJAX request to add the product to the cart
        var productId = productId; // Replace with the actual product ID
        var quantity = 1;  // Replace with the actual quantity
        $.ajax({
            type: 'POST',
            url: '{{ route('cart.add-to-cart', ['productId' => ':productId', 'quantity' => ':quantity']) }}'
            .replace(':productId', productId)
            .replace(':quantity', quantity),
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                console.log(response.message);
                
                // Display success message
                $('#success-message').text(response.message).removeClass('d-none');
                
                // You can update the UI or show a success message to the user

                // Hide success message after a brief delay (adjust the time as needed)
                setTimeout(function() {
                    $('#success-message').addClass('d-none').text('');

                    window.location.reload();
                }, 2000); // 2 seconds

                
            },
            error: function(error) {
                console.error('Error adding to cart:', error);
                // Handle errors and display an error message
            }
        });
    }
</script>
@endsection