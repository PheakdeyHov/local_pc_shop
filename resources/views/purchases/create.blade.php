@extends('layouts.app')
@section('contents')
    <section class="px-5">
        <div class="container-xl mt-5">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h4>Add Purchase</h4>
                        </div>
                        <div class="col-4 text-end">
                            <a href="{{ route('purchases.index') }}" class="btn btn-primary">Back</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="" id="addProductForm">
                        <div class="row">
                            <p class="ml-5">/ Product Detail</p>
                            <div class="col-md-6">
                                <div class="form-group mt-2">
                                    <label for="product_name" class="form-label">Product :</label>
                                    <select name="product_name" class="form-control shadow-none" id="product_select">
                                        <option value="" selected>Select Product</option>
                                        @foreach ($products as $product)
                                            <option class="shadow-none" value="{{ $product->id }}"
                                                {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group mt-2">
                                    <label for="specs_value" class="form-label">Specification :</label>
                                    <select name="specs_value" class="form-control shadow-none" id="specs_select">
                                        <option value="" selected>Select Specifications</option>
                                        @foreach ($specs as $spec)
                                            <option value="{{ $spec->id }}"
                                                {{ old('specification_id') == $spec->id ? 'selected' : '' }}>
                                                {{ $spec->specs_value }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group mt-2">
                                    <label for="qty" class="form-label">Quantity :</label>
                                    <input type="number" name="qty" class="form-control shadow-none" id="qty">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mt-2">
                                    <label for="purchase_price" class="form-label">Purchase Price :</label>
                                    <input type="text" name="purchase_price" class="form-control shadow-none"
                                        id="purchase_price">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mt-2">
                                    <label for="sale_price" class="form-label">Sale Price :</label>
                                    <input type="text" name="sale_price" class="form-control shadow-none"
                                        id="sale_price">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mt-2">
                                    <label for="status" class="form-label">Status :</label>
                                    <select name="specifications[0][status]" class="form-control shadow-none"
                                        id="status">
                                        <option value="" selected>Select Status</option>
                                        <option value="new" {{ old('status') == 'new' ? 'selected' : '' }}>New</option>
                                        <option value="second-hand" {{ old('status') == 'second-hand' ? 'selected' : '' }}>
                                            Second Hand</option>
                                        <option value="99" {{ old('status') == '99' ? 'selected' : '' }}>99%</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary" id="add-list">Add</button>
                            </div>
                        </div>
                    </form>

                    <div class="row mt-4">
                        <form action="{{ route('purchases.store') }}" method="POST" id="submitForm">
                            @csrf
                            <p class="ml-5">/ Supplier Detail</p>
                            <div class="row">
                                <div class="form-group mt-3">
                                    <label for="purchase_code" class="form-label">Purchase Code :</label>
                                    <input type="text" name="purchase_code" class="form-control shadow-none @error('purchase_code') is-invalid @enderror" value="{{ old('purchase_code') }}">
                                    @error('purchase_code')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group mt-3">
                                        <label for="supplier_id" class="form-label">Supplier Name :</label>
                                        <select name="supplier_id" class="form-control shadow-none @error('supplier_id') is-invalid @enderror" id="">
                                            <option value="" selected>Select Supplier</option>
                                            @foreach ($suppliers as $supplier)
                                                <option
                                                    value="{{ $supplier->id }} {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}">
                                                    {{ $supplier->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('supplier_id')
                                            <div class="text-danger">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group mt-3">
                                        <label for="shipping_company" class="form-label">Shipping Company :</label>
                                        <input type="text" name="shipping_company"
                                            class="form-control shadow-none @error('shipping_company') is-invalid @enderror"
                                            value="{{ old('shipping_company') }}" id="">
                                        @error('shipping_company')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group mt-3">
                                        <label for="shipping_price" class="form-label">Shipping Price :</label>
                                        <input type="text" name="shipping_price"
                                            class="form-control shadow-none @error('shipping_price') is-invalid @enderror"
                                            value="{{ old('shipping_price') }}" id="shippingPrice">
                                        @error('shipping_price')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group mt-3">
                                        <label for="shipping_status" class="form-label">Shipping Status :</label>
                                        <select name="shipping_status" class="form-control shadow-none @error('shipping_status') is-invalid @enderror" id="">
                                            <option value="" selected>Select Status</option>
                                            <option value="ordered"
                                                {{ old('shipping_status') == 'ordered' ? 'selected' : '' }}>Ordered
                                            </option>
                                            <option value="recieved"
                                                {{ old('shipping_status') == 'recieved' ? 'selected' : '' }}>Recieved
                                            </option>
                                            <option value="pending"
                                                {{ old('shipping_status') == 'pending' ? 'selected' : '' }}>Pending
                                            </option>
                                        </select>
                                        @error('shipping_status')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>


                            <div class="row mt-5">
                                <div class="card p-4">
                                    <div class="card-head">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h5>Product List</h5>

                                        </div>
                                        <!-- This is will save in PurchaseProduct Migration -->
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th style= "width: 250px;">Name</th>
                                                    <th>Specs</th>
                                                    <th style= "width: 100px;">Qty</th>
                                                    <th style= "width: 150px;">Purchase</th>
                                                    <th style= "width: 100px;">Sale</th>
                                                    <th style= "width: 200px;">Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="product-list">
                                                <!-- This is where product display -->
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="3"></td>
                                                    <td colspan="2" class="text-start">Total :</td>
                                                    <td colspan="2">
                                                        <input type="number" name="total_price"
                                                            class="form-control shadow-none @error('total_price')
                                                                is-invalid
                                                            @enderror" id="totalPrice" value="{{ old('total_price') }}" readonly>
                                                        @error('total_price')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3"></td>
                                                    <td colspan="2" class="text-start">Paid :</td>
                                                    <td colspan="2">
                                                        <input type="text" name="paid_price"
                                                            class="form-control shadow-none @error('paid_price') is-invalid @enderror" value="{{ old('paid_price') }}" id="paidPrice">
                                                        @error('paid_price')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3"></td>
                                                    <td colspan="2" class="text-start">Not Paid :</td>
                                                    <td colspan="2">
                                                        <input type="text" name="notpaid_price"
                                                            class="form-control shadow-none @error('notpaid_price') is-invalid @enderror" value="{{ old('notpaid_price') }}" id="notPaidPrice" readonly>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <!-- This button is for save button -->
                                <button class="btn btn-success">Save</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script type="module">
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Select2 for product and specs select
            const productSelect = document.getElementById('product_select');
            const specsSelect = document.getElementById('specs_select');

            // You can replace the Select2 initialization with custom code or use a fallback method
            $(productSelect).select2({
                tags: true,
                theme: 'bootstrap-5',
            });
            $(specsSelect).select2({
                tags: true,
                theme: 'bootstrap-5',
            });

            // Declare Total and Not Paid Price
            let totalPrice = 0;
            let notPaidPrice = 0;
            let rowIndex = 0; // Start with row index at 0

            // Function to Recalculate Prices
            const recalculatePrices = () => {
                totalPrice = 0;

                // Get all product rows
                const rows = document.querySelectorAll('#product-list tr');
                rows.forEach(row => {
                    const qty = parseInt(row.querySelector('.qty-input').value);
                    const purchase = parseFloat(row.querySelector('.purchase-price input')
                    .value); // Ensure we read text content here
                    if (!isNaN(qty) && !isNaN(purchase)) {
                        totalPrice += qty * purchase; // Multiply quantity and purchase price
                    }
                });

                // Include shipping price in total calculation
                const shippingPrice = parseFloat(document.getElementById('shippingPrice').value) || 0;
                const finalTotalPrice = totalPrice + shippingPrice; // Add shipping price to total

                // Update total price input field
                document.getElementById('totalPrice').value = finalTotalPrice.toFixed(2);

                // Calculate not paid price
                const paidPrice = parseFloat(document.getElementById('paidPrice').value) || 0;
                notPaidPrice = Math.max(finalTotalPrice - paidPrice, 0);
                document.getElementById('notPaidPrice').value = notPaidPrice.toFixed(2);
            };

            // Trigger recalculation when Paid Price or Shipping Price changes
            document.getElementById('paidPrice').addEventListener('input', recalculatePrices);
            document.getElementById('shippingPrice').addEventListener('input', recalculatePrices);

            // Add Product to Table List
            const addProductForm = document.getElementById('addProductForm');
            addProductForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Declare Variables
                const pro_id = productSelect.value;
                const pro_name = productSelect.options[productSelect.selectedIndex].text;
                const spec_id = specsSelect.value;
                const specs = specsSelect.options[specsSelect.selectedIndex].text;
                const qty = parseInt(document.getElementById('qty').value);
                const purchase = parseFloat(document.getElementById('purchase_price').value);
                const sale = parseFloat(document.getElementById('sale_price').value);
                const status = document.getElementById('status').value;

                if (isNaN(qty) || qty <= 0 || isNaN(purchase) || isNaN(sale)) {
                    alert('Please enter valid quantity and prices.');
                    return;
                }

                // Determine if product_id should be null
                const finalProductId = isNaN(pro_id) || pro_id === '' ? '' : pro_id;
                const finalSpecId = isNaN(spec_id) || spec_id === '' ? '' : spec_id;

                // Create a new row with a dynamic index
                const rowHtml = `
                    <tr>
                        <td style= "width: 250px;">
                            <input type="text" name="purchaseproducts[${rowIndex}][product_name]" class="form-control shadow-none" value="${pro_name}" readonly required>
                            <input type="text" name="purchaseproducts[${rowIndex}][product_id]" class="form-control shadow-none" value="${finalProductId}" readonly>
                        </td>
                        <td>
                            <textarea name="purchaseproducts[${rowIndex}][specs_value]" class="form-control shadow-none" required>${specs}</textarea>
                            <textarea name="purchaseproducts[${rowIndex}][specification_id]" class="form-control shadow-none"  readonly>${finalSpecId}</textarea>
                        </td>
                        <td style= "width: 100px;">
                            <input type="number" name="purchaseproducts[${rowIndex}][qty]" class="form-control qty-input shadow-none" value="${qty}" required>
                        </td>
                        <td class="purchase-price" style= "width: 150px;">
                            <input type="text" name="purchaseproducts[${rowIndex}][purchase_price]" class="form-control shadow-none" readonly value="${purchase.toFixed(2)}" required>
                        </td>
                        <td style= "width: 100px;"><input type="text" name="purchaseproducts[${rowIndex}][sale_price]" class="form-control shadow-none" readonly value="${sale.toFixed(2)}" required></td>
                        <td style= "width: 200px;"><input type="text" name="purchaseproducts[${rowIndex}][status]" class="form-control shadow-none" readonly value="${status}" required></td>
                        <td>
                            <button class="btn btn-danger btn-sm deleteRow">🗑</button>
                        </td>
                    </tr>
                `;

                // Add the row to the table
                document.querySelector('#product-list').insertAdjacentHTML('beforeend', rowHtml);

                // Recalculate Prices
                recalculatePrices();

                // Increment rowIndex for next row
                rowIndex++;

                // Clear form fields after adding
                addProductForm.reset();

                // Clear the select elements and trigger 'change' event
                productSelect.value = null;
                specsSelect.value = null;
                $(productSelect).trigger('change');
                $(specsSelect).trigger('change');
            });

            // Handle Row Deletion
            const productList = document.getElementById('product-list');
            productList.addEventListener('click', function(e) {
                if (e.target.classList.contains('deleteRow')) {
                    const row = e.target.closest('tr');
                    row.remove();
                    recalculatePrices(); // Recalculate after removing a row
                }
            });

            // Handle Quantity Change and Recalculate Prices
        productList.addEventListener('input', function(e) {
            if (e.target.classList.contains('qty-input')) {
                const qtyInput = e.target;
                const qty = parseInt(qtyInput.value);
                // Ensure quantity doesn't go below 0
                if (qty <= 0) {
                    const row = qtyInput.closest('tr');
                    row.remove();
                    recalculatePrices(); // Remove the row if quantity is less than 0
                } else {
                    recalculatePrices();
                }
            }
        });
        });
    </script>
@endsection
