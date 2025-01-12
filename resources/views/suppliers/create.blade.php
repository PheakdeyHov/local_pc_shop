@extends('layouts.app')
@section('contents')
<section class="px-5">
    <div class="container-xl mt-5">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-8">
                        <h5>Add Supplier</h5>
                    </div>
                    <div class="col-4 text-end">
                        <a href="{{ route('suppliers.index') }}" class="btn btn-primary btn-sm">Back</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('suppliers.store') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="name" class="form-label">Supplier Name :</label>
                                <input type="text" name="name" class="form-control shadow-none @error('name') is-invalid @enderror" value="{{ old('name') }}" id="">
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="phonenumber">Phonenumber :</label>
                                <input type="text" name="phonenumber" class="form-control shadow-none @error('phonenumber') is-invalid @enderror" value="{{ old('phonenumber') }}" id="">
                                @error('phonenumber')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="email">Email :</label>
                                <input type="text" name="email" class="form-control shadow-none @error('email') is-invalid @enderror" value="{{ old('email') }}" id="">
                                @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="address">Address :</label>
                                <textarea name="address" class="form-control shadow-none @error('address') is-invalid @enderror" id="" cols="30" rows="10">{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
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
