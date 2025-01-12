@extends('layouts.app')
@section('contents')
<section class="px-5">
    <div class="container-xl mt-5">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-8">
                        <h5>Add Category</h5>
                    </div>
                    <div class="col-4 text-end">
                        <a href="{{ route('categories.index') }}" class="btn btn-primary btn-sm">Back</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('categories.store') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="name" class="form-label">Category Name :</label>
                        <input type="text" name="name" class="form-control shadow-none @error('name') is-invalid @enderror" value="{{ old('name') }}" id="">
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-success btn-sm">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
