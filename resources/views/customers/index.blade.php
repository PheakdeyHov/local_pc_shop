@extends('layouts.app')
@section('contents')
<div class="px-5">
    <div class="container-xl mt-5">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-8">
                        <h4>Customer List</h4>
                        <div class="search-input me-1">
                            <form action="#" id="searchForm">
                                <div class="form-group">
                                    <input type="text" id="searchInput" value="{{ request('name') }}" name="search" placeholder="Search..." class="form-control shadow-none">
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="{{ route('customers.create') }}" class="btn btn-primary">Add New</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="searchTable">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>N.O.</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Phonenumber</th>
                                <th>Customer Type</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($customers->isEmpty())
                                <tr>
                                    <td colspan="6" class="text-center">No customer found</td>
                                </tr>
                            @else
                                @foreach ($customers as $key=>$customer)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $customer->name }}</td>
                                        <td>{{ $customer->address }}</td>
                                        <td>{{ $customer->phonenumber }}</td>
                                        <td>
                                            @if ($customer->customer_type == 0)
                                                <span class="badge bg-success">Walk In Customer</span>
                                            @else
                                                <span class="badge bg-info">Daily Customer</span>
                                            @endif
                                        </td>
                                        <td>
                                            <form id="delete-form-{{ $customer->id }}" action="{{ route('customers.destroy', $customer->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <a href="{{ route('customers.edit' , $customer->id) }}" class="btn btn-primary"><i class="bi bi-pencil-square"></i></a>
                                                <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $customer->id }})"><i class="bi bi-trash"></i></button>
                                            </form
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
@include('alert.question')
@endsection
@section('scripts')
<script type="module">

    $('#searchInput').on('input', function() {
        const searchTerm = $(this).val();

        // Prevent default form submission and keep the cursor in the input
        if (searchTerm.length >= 0) {
            fetch(`{{ route('customers.search') }}?name=${searchTerm}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest', // Indicate this is an AJAX request
                },
            })
            .then(response => response.text())
            .then(data => {
                // Update the category table with the fetched data
                $('#searchTable').html($(data).find('#searchTable').html());
            })
            .catch(error => console.error('Error:', error));
        }
    });

</script>
@endsection
