<!-- resources/views/products/create.blade.php -->

@extends(
    'layouts.' .
        (auth()->user()->isAdmin()
            ? 'admin'
            : 'vendor')
)

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Create Product') }}</div>

                <div class="card-body">
                    <!-- Display validation errors -->
                    @if ($errors->any())
                       <div class="alert alert-danger">
                           <ul>
                               @foreach ($errors->all() as $error)
                                   <li>{{ $error }}</li>
                               @endforeach
                           </ul>
                       </div>
                   @endif
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form id="create-product-form" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="main_image" class="form-label">Main Image</label>
                            <input type="file" class="form-control" id="main_image" name="main_image" required>
                            <div id="image-preview"></div>
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" class="form-control" id="price" name="price" required min="0.01" step="0.01">
                        </div>
                        
                        <div class="mb-3">
                            <label for="stock" class="form-label">Stock</label>
                            <input type="number" class="form-control" id="stock" name="stock" required min="0">
                        </div>

                        <button type="button" class="btn btn-primary" onclick="submitForm()">Create Product</button>
                    </form>
                    <br>
                     <!-- Additional: Bootstrap alert components for AJAX responses -->
                     <div id="ajax-errors" class="alert alert-danger d-none"></div>
                     <div id="ajax-success" class="alert alert-success d-none"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>



    function submitForm() {
        var form = $('#create-product-form')[0];
        var formData = new FormData(form);

        $.ajax({
            type: 'POST',
            url: '{{ route('products.store') }}',
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
               // Display success message
                $('#ajax-errors').addClass('d-none');
                $('#ajax-success').text(data.success).removeClass('d-none');

                // Hide success message after 5 seconds
                setTimeout(function () {
                    $('#ajax-success').addClass('d-none');

                    window.location.href = '{{ route('products.index') }}';
                }, 2000);

            },
            error: function (error) {
               // Handle errors and display error messages
               if (error.status === 422) {
                    var errors = error.responseJSON.errors;

                    // Display validation errors
                    var errorMessages = '';
                    $.each(errors, function(key, value) {
                        errorMessages += value[0] + '<br>';
                    });
                    $('#ajax-errors').html(errorMessages).removeClass('d-none');
                } else {
                    // Handle other types of errors
                    console.error(error);
                }
            }
        });
    }

    // Display image preview on file selection
    $('#main_image').on('change', function () {
        var input = this;

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#image-preview').html('<img src="' + e.target.result + '" alt="Image Preview" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">');
            };

            reader.readAsDataURL(input.files[0]);
        }
    });
</script>
@endsection
