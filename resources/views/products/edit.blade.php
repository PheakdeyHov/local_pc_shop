@extends('layouts.app')
@section('contents')
<section class="px-5">
    <div class="container-xl mt-5">
        <div class="row my-4">
            <div class="col mx-auto">
                <div class="card shadow">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-8">
                                <h5>Update Product</h5>
                            </div>
                            <div class="col-4 text-end">
                                <a href="{{ route('products.index') }}" class="btn btn-primary btn-sm">Back</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('products.update',$products->id) }}" method="POST" enctype="multipart/form-data">
                            @method('PUT')
                            @csrf
                            <div class="row">
                                <p class="text-muted fs-sm-6 fs-md-4 fs-lg-2">/ Product Detail</p>
                                <div class="row">
                                    <div class="col-7">
                                        <div class="form-group mt-3">
                                            <label for="name" class="form-label">Product Name :</label>
                                            <input type="text" name="name" class="form-control shadow-none" value="{{ $products->name }}">
                                        </div>
                                        <div class="form-group mt-3">
                                            <label for="category_id" class="form-label">Category :</label>
                                            <select name="category_id" class="form-control shadow" id="">
                                                <option value="" selected>Please select category</option>
                                                @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ $products->category_id == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('categories_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group mt-3">
                                            <label for="brand_id" class="form-label">Brand :</label>
                                            <select name="brand_id" class="form-control shadow" id="">
                                                <option value="" selected>Please select brand</option>
                                                @foreach ($brands as $brand)
                                                <option value="{{ $brand->id }}"
                                                    {{ $products->brand_id == $brand->id ? 'selected' : '' }}>
                                                    {{ $brand->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('brand_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-5">
                                        <div class="form-group mt-3">
                                            <label for="image" class="form-label">Product Image :</label>
                                            <div class="container">
                                                <div class="wrapper {{ $products->image ? 'active' : '' }}">
                                                    <div class="image">
                                                        <img src="{{ $products->image ? asset('storage/' . $products->image) : '' }}" alt="Product image">
                                                    </div>
                                                    <div class="content">
                                                        <div class="icon"><i class="bi bi-upload"></i></div>
                                                        <div class="text">No file chosen</div>
                                                    </div>
                                                    <div id="cancel-btn"><i class="bi bi-x-lg"></i></div>
                                                    <div class="file-name">File Name</div>
                                                </div>
                                                <input type="file" hidden name="logo" class="form-control shadow-none" id="default-btn">
                                                <button id="custom-btn" onclick="defaultBtnActive()">Choose a Image</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <p>/ Specifications Detail</p>
                                <div class="row">
                                    <table class="table table-bordered">
                                        <thead class="text-center">
                                            <tr>
                                                <th>Product Code</th>
                                                <th>Specifications Value</th>
                                                <th>Qauntity</th>
                                                <th>Purchase Price</th>
                                                <th>Sale Price</th>
                                                <th>Status</th>
                                                <th>
                                                    <button class="btn btn-primary btn-sm" id="add-row" type="button">Add Row</button>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center" id="repeater-body">
                                            @foreach ($products->specifications as $index => $specification)
                                            <tr>
                                                <td><input type="text" class="form-control shadow-none" name="specifications[{{ $index }}][product_code]" value="{{ $specification->product_code }}" id=""></td>
                                                <td><textarea name="specifications[{{ $index }}][specs_value]" class="form-control shadow-none" id="" cols="15" rows="5">{{ $specification->specs_value }}</textarea></td>
                                                <td><input type="number" class="form-control shadow-none" name="specifications[{{ $index }}][qty]" value="{{ $specification->qty }}" id=""></td>
                                                <td><input type="text" class="form-control shadow-none" name="specifications[{{ $index }}][purchase_price]" value="{{ $specification->purchase_price }}" id=""></td>
                                                <td><input type="text" class="form-control shadow-none" name="specifications[{{ $index }}][sale_price]" value="{{ $specification->sale_price }}" id=""></td>
                                                <td>
                                                    <select name="specifications[{{ $index }}][status]" class="form-control shadow-none" id="">
                                                        <option value="" selected>Select Status</option>
                                                        <option value="new" {{ $specification->status == 'new' ? 'selected' : '' }}>New</option>
                                                        <option value="second-hand" {{ $specification->status == 'second-hand' ? 'selected' : '' }}>Second Hand</option>
                                                        <option value="99" {{ $specification->status == '99' ? 'selected' : '' }}>99%</option>
                                                    </select>
                                                    @error('status')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <button class="btn btn-danger btn-sm remove-row">Remove</button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="mt-5">
                                <button type="submit" class="btn btn-success btn-sm">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<script>
    // Start of Image Preview
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
    // End of Image Preview

    // Start of Form Repeater


    document.addEventListener('DOMContentLoaded', function () {
    const repeaterBody = document.getElementById('repeater-body');
    const addRowButton = document.getElementById('add-row');

    // Dynamically add a new row
    addRowButton.addEventListener('click', function (e) {
        e.preventDefault();

        const rowIndex = repeaterBody.children.length; // Update the index dynamically
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td><input type="text" class="form-control shadow-none" name="specifications[${rowIndex}][product_code]" id=""></td>
            <td><textarea name="specifications[${rowIndex}][specs_value]" class="form-control shadow-none" id="" cols="15" rows="5"></textarea></td>
            <td><input type="number" class="form-control shadow-none" name="specifications[${rowIndex}][qty]" id=""></td>
            <td><input type="text" class="form-control shadow-none" name="specifications[${rowIndex}][purchase_price]" id=""></td>
            <td><input type="text" class="form-control shadow-none" name="specifications[${rowIndex}][sale_price]" id=""></td>
            <td>
                <select name="specifications[${rowIndex}][status]" class="form-control shadow-none" id="">
                    <option value="" selected>Select Status</option>
                    <option value="new">New</option>
                    <option value="second-hand">Second Hand</option>
                    <option value="99">99%</option>
                </select>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm remove-row">Remove</button>
            </td>
        `;

        // Append the new row
        repeaterBody.appendChild(newRow);
    });

    // Remove a row
    repeaterBody.addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('remove-row')) {
            e.preventDefault();
            e.target.closest('tr').remove();
        }
    });
});

    // End of Form Repeater
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
