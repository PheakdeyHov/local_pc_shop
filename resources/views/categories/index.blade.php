@extends('layouts.app')
@section('contents')
<section class="px-5">
    <div class="container-xl mt-5">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-8">
                        <h5>Categories List</h5>
                        <div class="search-input me-1">
                            <form action="#" id="searchForm">
                                <div class="form-group">
                                    <input type="text" id="searchInput" value="{{ request('name') }}" name="search" placeholder="Search..." class="form-control shadow-none">
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <a href="{{ route('categories.create') }}" class="btn btn-primary btn-sm">Add New</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="searchTable">
                    <table class="table table-hovered table-bordered" id="searchTable">
                        <thead>
                            <tr>
                                <th>N.O.</th>
                                <th>Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($categories->isEmpty())
                                <tr>
                                    <td colspan="3" class="text-center">No categories found</td>
                                </tr>
                            @else
                                @foreach ($categories as $key=>$category)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $category->name}}</td>
                                        <td>
                                            <form id="delete-form-{{ $category->id }}" action="{{ route('categories.destroy', $category->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <a href="{{ route('categories.edit' , $category->id) }}" class="btn btn-primary"><i class="bi bi-pencil-square"></i></a>
                                                <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $category->id }})"><i class="bi bi-trash"></i></button>
                                            </form>
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
</section>
@include('alert.question')
@endsection
@section('scripts')
<script type="module">

    $('#searchInput').on('input', function() {
        const searchTerm = $(this).val();

        // Prevent default form submission and keep the cursor in the input
        if (searchTerm.length >= 0) {
            fetch(`{{ route('categories.search') }}?name=${searchTerm}`, {
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
