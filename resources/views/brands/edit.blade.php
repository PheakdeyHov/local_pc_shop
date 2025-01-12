@extends('layouts.app')
@section('contents')
<section class="px-5">
    <div class="mt-5">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-8">
                        <h5>Edit Brand</h5>
                    </div>
                    <div class="col-4 text-end">
                        <a href="{{ route('brands.index') }}" class="btn btn-primary btn-sm">Back</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('brands.update', $brands->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-7">
                            <div class="form-group">
                                <label for="name" class="form-label">Brand Name:</label>
                                <input type="text" name="name" class="form-control shadow-none @error('name') is-invalid @enderror" value="{{ old('name', $brands->name) }}">
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="status" class="form-label">Status:</label>
                                <select name="status" class="form-control shadow-none @error('status') is-invalid @enderror" id="status">
                                    <option value="" {{ $brands->status === null ? 'selected' : '' }}>Please select status</option>
                                    <option value="0" {{ $brands->status == '0' ? 'selected' : '' }}>Available</option>
                                    <option value="1" {{ $brands->status == '1' ? 'selected' : '' }}>Unavailable</option>
                                </select>
                                @error('status')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="form-group">
                                <label for="logo" class="form-label">Brand Logo:</label>
                                <div class="container">
                                    <div class="wrapper {{ $brands->logo ? 'active' : '' }}">
                                        <div class="image">
                                            <img src="{{ $brands->logo ? asset('storage/' . $brands->logo) : '' }}" alt="Brand Logo">
                                        </div>
                                        <div class="content">
                                            <div class="icon"><i class="bi bi-upload"></i></div>
                                            <div class="text">No file chosen</div>
                                        </div>
                                        <div id="cancel-btn"><i class="bi bi-x-lg"></i></div>
                                        <div class="file-name">File Name</div>
                                    </div>
                                    <input type="file" hidden name="logo" class="form-control shadow-none" id="default-btn">
                                    <button id="custom-btn" onclick="defaultBtnActive()">Choose a logo</button>
                                </div>
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

<script>
    const wrapper = document.querySelector(".wrapper");
    const fileName = document.querySelector(".file-name");
    const defaultBtn = document.querySelector('#default-btn');
    const cancelBtn = document.querySelector('#cancel-btn');
    const customBtn = document.querySelector('#custom-btn');
    const img = document.querySelector('img');
    let regExp = /[0-9a-zA-Z\^\&\`\@\{\}\[\]\,\$\=\!\-\#\(\)\.\%\+\~\_ ]+$/;
    function defaultBtnActive(){
        event.preventDefault();
        defaultBtn.click();
    }
    defaultBtn.addEventListener("change", function(){
        const file = this.files[0];
        if(file){
            const reader = new FileReader();
            reader.onload = function () {
                const result = reader.result;
                img.src = result;
                wrapper.classList.add("active");
            }
            cancelBtn.addEventListener("click", function(){
                img.src = "";
                wrapper.classList.remove("active");
            });
            reader.readAsDataURL(file);
        }
        if(this.value){
            let valueStore = this.value.match(regExp);
            fileName.textContent = valueStore;
        }
    });
</script>

<style>
    .container {
        display: grid;
        width: 430px;
        height: 350px;
        align-items: center;
        place-items: center;

    }
    .container .wrapper {
        position: relative;
        height: 300px;
        width: 100%;
        border-radius: 10px;
        background: #fff;
        border: 2px dashed #c2cdda;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .wrapper .image {
        position: absolute;
        height: 100%;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .wrapper .image img{
        height: 100%;
        width: 100%;
        background-color: transparent;
        border: none;
        object-fit: contain;
    }
    .wrapper .image img[src=""] {
        display: none; /* Hide the image tag entirely if src is empty */
    }
    .wrapper .icon{
        font-size: 100px;
        color: blue;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .wrapper .text{
        font-size: 20px;
        font-weight: 500;
        color: blue;
    }
    .wrapper #cancel-btn {
        position: absolute;
        right: 15px;
        top: 15px;
        font-size: 20px;
        cursor: pointer;
        color:  blue;
        display: none;
    }
    .wrapper.active:hover #cancel-btn{
        display: block;
    }

    .wrapper #cancel-btn:hover{
        color: red;
    }

    .wrapper .file-name {
        position: absolute;
        bottom: 0px;
        width: 100%;
        color: #fff;
        padding: 8px 0;
        font-size: 18px;
        background: blue;
        text-align: center;
        display: none;
    }
    .wrapper.active:hover .file-name{
        display: block;
    }
    .container #custom-btn {
        margin-top: 10px;
        width: 50%;
        height: 40px;
        display: block;
        border: none;
        outline: none;
        border-radius: 25px;
        color: #fff;
        font-size: 15px;
        font-weight: 500;
        letter-spacing: 1px;
        text-transform: uppercase;
        cursor: pointer;
        background: blue;
    }
</style>
@endsection
