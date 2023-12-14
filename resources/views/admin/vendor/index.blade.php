@extends('layouts.admin')

@section('content')
    <div class="container">
        <h2>Vendors</h2>

        <!-- Display success message if it exists -->
        @if(session('success'))
             <div class="alert alert-success">
                 {{ session('success') }}
             </div>
         @endif

        <!-- Success and error messages -->
        <div id="success-message" class="alert alert-success d-none"></div>
        <div id="error-message" class="alert alert-danger d-none"></div>

        <!-- Add the "Create Vendor" button -->
        <a href="{{ route('admin.vendor.create') }}" class="btn btn-primary mb-3">Create Vendor</a>

        @if ($vendors->isEmpty())
            <p>No vendors.</p>
        @else
            <table class="table" id="vendors-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($vendors as $vendor)
                        <tr>
                            <td class="border">{{ $vendor->name }}</td>
                            <td class="border">{{ $vendor->email }}</td>
                            <td class="border">
                                <a href="{{ route('admin.vendor.edit', $vendor->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <script>
        $(document).ready(function() {
            $('#vendors-table').DataTable();
        });
    </script>

    <style>
        .border {
            border: 1px solid #dee2e6;
            padding: 8px;
        }
    </style>

@endsection
