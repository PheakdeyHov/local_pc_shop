@extends('layouts.app')
@section('contents')
<div class="px-5">
    <div class="container-xl mt-5">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-8">
                        <h4>Create Customer</h4>
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="{{ route('customers.index') }}" class="btn btn-primary">Back</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('customers.store') }}" method="POST">
                    @csrf
                    <div class="mt-3">
                        <label for="customer_type" class="form-label">Customer Type :</label>
                        <select name="customer_type" class="form-control shadow-none @error('customer_type') is-invalid @enderror" id="">
                            <option value="" selected>Please select customer type</option>
                            <option value="0" {{ old('customer_type') === '0' ? 'selected' : '' }}>Walk in customer</option>
                            <option value="1" {{ old('customer_type') === '1' ? 'selected' : '' }}>Daily customer</option>
                        </select>
                        @error('customer_type')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mt-3">
                        <label for="name" class="form-label">Name :</label>
                        <input type="text" class="form-control shadow-none @error('name') is-invalid @enderror" value="{{ old('name') }}" name="name">
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mt-3">
                        <label for="phonenumber" class="form-label">Phonenumber :</label>
                        <input type="text" class="form-control shadow-none @error('phonenumber') is-invalid @enderror" value="{{ old('phonenumber') }}" name="phonenumber" id="">
                        @error('phonenumber')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mt-3">
                        <label for="address" class="form-label">Address :</label>
                        <textarea class="form-control shadow-none @error('address') is-invalid @enderror" name="address" id="" cols="30" rows="10">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@include('alert.question')
@endsection
