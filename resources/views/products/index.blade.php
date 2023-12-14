<!-- resources/views/products/index.blade.php -->


@extends(
    'layouts.' .
        (auth()->user()->isAdmin()
            ? 'admin'
            : 'vendor')
)

@section('content')
    <div class="container">
        <h2>Your Products</h2>

        <!-- Success and error messages -->
        <div id="success-message" class="alert alert-success d-none"></div>
        <div id="error-message" class="alert alert-danger d-none"></div>

        <!-- Add the "Create Product" button -->
        <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">Create Product</a>

        @if ($products->isEmpty())
            <p>You have no products.</p>
        @else
            <table class="table" id="products-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Main Image</th>
                        <th>Price</th>
                        <th>Stock</th>
                        @if (auth()->user()->isAdmin())
                            <th>Vendor Name</th>
                            <th>Vendor Email</th>
                        @endif
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td class="border">{{ $product->title }}</td>
                            <td class="border">{{ $product->description }}</td>
                            <td class="border">
                                @if ($product->main_image)
                                    <img src="{{ asset('storage/' . $product->main_image) }}" alt="Main Image"
                                        style="max-width: 100px; max-height: 100px;">
                                @else
                                    No Image
                                @endif
                            </td>
                            <td class="border">
                                {{ $product->price }}
                            </td>
                            <td class="border">
                                {{ $product->stock }}
                            </td>
                            @if (auth()->user()->isAdmin())
                                <td class="border">{{ $product->vendor->name }}</td>
                                <td class="border">{{ $product->vendor->email }}</td>
                            @endif
                            <td class="border">
                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <button class="btn btn-danger btn-sm"
                                    onclick="deleteProduct({{ $product->id }})">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <script>
        $(document).ready(function() {
            $('#products-table').DataTable();
        });

        function deleteProduct(productId) {
            if (confirm("Are you sure you want to delete this product?")) {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('products.destroy', '') }}/' + productId,
                    data: {
                        _method: 'DELETE',
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log('Product deleted successfully');
                        // Show success message
                        $('#success-message').text('Product deleted successfully').removeClass('d-none');


                        // Reload the page after a brief delay (adjust the time as needed)
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    },
                    error: function(error) {
                        console.error('Error deleting product:', error);
                        // Show error message
                        $('#error-message').text('Error deleting product').removeClass('d-none');
                    }
                });
            }
        }
    </script>

    <style>
        .border {
            border: 1px solid #dee2e6;
            padding: 8px;
        }
    </style>

@endsection
