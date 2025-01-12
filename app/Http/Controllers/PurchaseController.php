<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseProducts;
use App\Models\Specification;
use App\Models\Supplier;
use Illuminate\Console\View\Components\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function search(Request $request)
    {
        $searchTerm = $request->get('name'); // Get the search term from the request

        // Query to filter categories based on the 'name' field
        if ($searchTerm) {
            // Filter categories by name
            $purchases = Purchase::where('name', 'like', '%' . $searchTerm . '%')->get();
        } else {
            // If no search term, return all categories
            $purchases = Purchase::all();
        }

        return view('purchases.index', compact('purchases'));
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchases = Purchase::all();
        $suppliers = Supplier::all();
        return view('purchases.index', compact('purchases', 'suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::all();
        $specs = Specification::all();
        $suppliers = Supplier::all();
        return view('purchases.create', compact('products', 'specs', 'suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'purchase_code' => 'nullable|string|unique:purchases,purchase_code',
            'supplier_id' => 'required|exists:suppliers,id',
            'shipping_company' => 'required|string',
            'shipping_price' => 'required|numeric|min:0',
            'shipping_status' => 'required|string|in:ordered,recieved,pending',
            'total_price' => 'required|numeric|min:0',
            'paid_price' => 'required|numeric|min:0',
            'notpaid_price' => 'required|numeric|min:0',
            'purchaseproducts.*.product_name' => 'required|string',
            'purchaseproducts.*.specs_value' => 'required|string',
            'purchaseproducts.*.qty' => 'required|integer|min:0',
            'purchaseproducts.*.purchase_price' => 'required|numeric|min:0',
            'purchaseproducts.*.sale_price' => 'required|numeric|min:0',
            'purchaseproducts.*.status' => 'required|string|in:new,second-hand,99',
        ]);

        DB::transaction(function () use ($validatedData) {
            $purchase = Purchase::create([
                'purchase_code' => $validatedData['purchase_code'],
                'supplier_id' => $validatedData['supplier_id'],
                'shipping_company' => $validatedData['shipping_company'],
                'shipping_price' => $validatedData['shipping_price'],
                'shipping_status' => $validatedData['shipping_status'],
                'total_price' => $validatedData['total_price'],
                'paid_price' => $validatedData['paid_price'],
                'notpaid_price' => $validatedData['notpaid_price'],
            ]);
            foreach ($validatedData['purchaseproducts'] as $product) {
                if ($validatedData['shipping_status'] === 'recieved') {
                    $this->handleReceivedProduct($product, $purchase);
                } else {
                    $productModel = Product::where('name', $product['product_name'])->first();

                    if ($productModel) {
                        // Check if the specification exists
                        $specification = Specification::where('product_id', $productModel->id)
                            ->where('specs_value', $product['specs_value'])
                            ->where('purchase_price', $product['purchase_price'])
                            ->where('sale_price', $product['sale_price'])
                            ->where('status', $product['status'])
                            ->first();

                        // Create the purchase product with or without specification
                        $this->createNewPurchaseProductWithSpecification(
                            $product,
                            $purchase,
                            $productModel,
                            $specification // Pass null if specification is not found
                        );
                    }
                } // Check if the product exists

            }
        });

        toastr()->success('Purchase created successfully!');
        return redirect()->route('purchases.index');
    }
    private function handleReceivedProduct(array $product, Purchase $purchase)
    {
        $productModel = Product::where('name', $product['product_name'])->first();

        if ($productModel) {

            // Ensure category_id and brand_id are set to 1 if not already
            if (!$productModel->category_id) {
                $productModel->update(['category_id' => 1]);
            }
            if (!$productModel->brand_id) {
                $productModel->update(['brand_id' => 1]);
            }

            $specification = Specification::where('product_id', $productModel->id)
                ->where('specs_value', $product['specs_value'])
                ->where('purchase_price', $product['purchase_price'])
                ->where('sale_price', $product['sale_price'])
                ->where('status', $product['status'])
                ->first();

            if ($specification) {
                // Check if purchase product exists and update quantity
                $purchaseProduct = $purchase->purchaseProducts()
                    ->where('product_id', $productModel->id)
                    ->where('specification_id', $specification->id)
                    ->first();

                if ($purchaseProduct) {
                    // Update purchase product quantity
                    $purchaseProduct->update([
                        'qty' => $purchaseProduct->qty + $product['qty'],
                    ]);
                } else {
                    // Create new record if purchase product doesn't exist
                    $this->createNewPurchaseProductWithSpecification($product, $purchase, $productModel, $specification);
                }

                // Update specification quantity
                $specification->update([
                    'qty' => $specification->qty + $product['qty'],
                ]);
            } else {
                // Create new specification if not found
                $newSpecification = Specification::create([
                    'product_id' => $productModel->id,
                    'specs_value' => $product['specs_value'],
                    'purchase_price' => $product['purchase_price'],
                    'qty' => $product['qty'],  // Set the initial qty here
                    'sale_price' => $product['sale_price'],
                    'status' => $product['status'],
                ]);

                $this->createNewPurchaseProductWithSpecification($product, $purchase, $productModel, $newSpecification);
            }
        } else {
            // Create new product and specification if none found
            $newProduct = Product::create([
                'name' => $product['product_name'],
                'category_id' => 1, // Default to 1 if none exists
                'brand_id' => 1,
            ]);
            $newSpecification = Specification::create([
                'product_id' => $newProduct->id,
                'specs_value' => $product['specs_value'],
                'purchase_price' => $product['purchase_price'],
                'qty' => $product['qty'],
                'sale_price' => $product['sale_price'],
                'status' => $product['status'],
            ]);

            $this->createNewPurchaseProductWithSpecification($product, $purchase, $newProduct, $newSpecification);
        }
    }

    private function createNewPurchaseProductWithSpecification(
        array $product,
        Purchase $purchase,
        Product $productModel,
        ?Specification $specification // Nullable type for Specification
    ) {
        $purchase->purchaseProducts()->create([
            'product_id' => $productModel->id,
            'specification_id' => $specification ? $specification->id : null, // Use null if no specification exists
            'product_name' => $product['product_name'],
            'specs_value' => $product['specs_value'],
            'qty' => $product['qty'],
            'purchase_price' => $product['purchase_price'],
            'sale_price' => $product['sale_price'],
            'status' => $product['status'],
        ]);
    }



    // private function createNewPurchaseProduct(array $product, Purchase $purchase)
    // {
    //     $purchase->purchaseProducts()->create([
    //         'product_name' => $product['product_name'],
    //         'specs_value' => $product['specs_value'],
    //         'qty' => $product['qty'],
    //         'purchase_price' => $product['purchase_price'],
    //         'sale_price' => $product['sale_price'],
    //         'status' => $product['status'],
    //     ]);
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $purchases = Purchase::findOrFail($id);
        $suppliers = Supplier::all();
        $products = Product::all();
        $specs = Specification::all();

        return view('purchases.edit', compact('purchases', 'suppliers', 'products', 'specs'));
    }

    public function update(Request $request, $id)
{
    $validatedData = $request->validate([
        'purchase_code' => "nullable|string|unique:purchases,purchase_code,{$id}",
        'supplier_id' => 'required|exists:suppliers,id',
        'shipping_company' => 'required|string',
        'shipping_price' => 'required|numeric|min:0',
        'shipping_status' => 'required|string|in:ordered,recieved,pending',
        'total_price' => 'required|numeric|min:0',
        'paid_price' => 'required|numeric|min:0',
        'notpaid_price' => 'required|numeric|min:0',
        'purchaseproducts.*.product_name' => 'required|string',
        'purchaseproducts.*.specs_value' => 'required|string',
        'purchaseproducts.*.qty' => 'required|integer|min:0',
        'purchaseproducts.*.purchase_price' => 'required|numeric|min:0',
        'purchaseproducts.*.sale_price' => 'required|numeric|min:0',
        'purchaseproducts.*.status' => 'required|string|in:new,second-hand,99',
    ]);

    $purchase = Purchase::findOrFail($id);

    DB::transaction(function () use ($validatedData, $purchase) {
        // Update the main purchase record
        $purchase->update([
            'purchase_code' => $validatedData['purchase_code'],
            'supplier_id' => $validatedData['supplier_id'],
            'shipping_company' => $validatedData['shipping_company'],
            'shipping_price' => $validatedData['shipping_price'],
            'shipping_status' => $validatedData['shipping_status'],
            'total_price' => $validatedData['total_price'],
            'paid_price' => $validatedData['paid_price'],
            'notpaid_price' => $validatedData['notpaid_price'],
        ]);

        // Delete old purchase products
        $purchase->purchaseProducts()->delete();

        // Handle products associated with the purchase
        foreach ($validatedData['purchaseproducts'] as $product) {
            if ($validatedData['shipping_status'] === 'recieved') {
                $this->handleUpdateProduct($product, $purchase);
            } else {
                $productModel = Product::where('name', $product['product_name'])->first();

                if ($productModel) {
                    $specification = Specification::where('product_id', $productModel->id)
                        ->where('specs_value', $product['specs_value'])
                        ->where('purchase_price', $product['purchase_price'])
                        ->where('sale_price', $product['sale_price'])
                        ->where('status', $product['status'])
                        ->first();

                    $this->createNewPurchaseProductWithSpecification(
                        $product,
                        $purchase,
                        $productModel,
                        $specification
                    );
                }
            }
        }
    });

    toastr()->success('Purchase updated successfully!');
    return redirect()->route('purchases.index');
}


    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, $id)
    // {
    //     $validatedData = $request->validate([
    //         'purchase_code' => 'nullable|string|unique:purchases,purchase_code,' . $id,
    //         'supplier_id' => 'required|exists:suppliers,id',
    //         'shipping_company' => 'required|string',
    //         'shipping_price' => 'required|numeric|min:0',
    //         'shipping_status' => 'required|string|in:ordered,recieved,pending',
    //         'total_price' => 'required|numeric|min:0',
    //         'paid_price' => 'required|numeric|min:0',
    //         'notpaid_price' => 'required|numeric|min:0',
    //         'purchaseproducts.*.product_name' => 'required|string',
    //         'purchaseproducts.*.specs_value' => 'required|string',
    //         'purchaseproducts.*.qty' => 'required|integer|min:0',
    //         'purchaseproducts.*.purchase_price' => 'required|numeric|min:0',
    //         'purchaseproducts.*.sale_price' => 'required|numeric|min:0',
    //         'purchaseproducts.*.status' => 'required|string|in:new,second-hand,99',
    //     ]);

    //     DB::transaction(function () use ($validatedData, $id) {
    //         $purchase = Purchase::findOrFail($id);

    //         // Update the purchase record
    //         $purchase->update([
    //             'purchase_code' => $validatedData['purchase_code'],
    //             'supplier_id' => $validatedData['supplier_id'],
    //             'shipping_company' => $validatedData['shipping_company'],
    //             'shipping_price' => $validatedData['shipping_price'],
    //             'shipping_status' => $validatedData['shipping_status'],
    //             'total_price' => $validatedData['total_price'],
    //             'paid_price' => $validatedData['paid_price'],
    //             'notpaid_price' => $validatedData['notpaid_price'],
    //         ]);

    //         // Delete old purchase products
    //         $purchase->purchaseProducts()->delete();

    //         // Process each product
    //         foreach ($validatedData['purchaseproducts'] as $product) {
    //             if ($validatedData['shipping_status'] === 'recieved') {
    //                 $this->handleReceivedProductUpdate($product, $purchase);
    //             } else {
    //                 $this->updateOrCreatePurchaseProduct($product, $purchase);
    //             }
    //         }
    //     });

    //     toastr()->success('Purchase updated successfully!');
    //     return redirect()->route('purchases.index');
    // }

    // private function handleReceivedProductUpdate(array $product, Purchase $purchase)
    // {
    //     $productModel = Product::where('name', $product['product_name'])->first();

    //     if ($productModel) {

    //         // Ensure category_id and brand_id are set to 1 if not already
    //         if (!$productModel->category_id) {
    //             $productModel->update(['category_id' => 1]);
    //         }
    //         if (!$productModel->brand_id) {
    //             $productModel->update(['brand_id' => 1]);
    //         }

    //         $specification = Specification::where('product_id', $productModel->id)
    //             ->where('specs_value', $product['specs_value'])
    //             ->where('purchase_price', $product['purchase_price'])
    //             ->where('sale_price', $product['sale_price'])
    //             ->where('status', $product['status'])
    //             ->first();

    //         if ($specification) {
    //             // Update the existing purchase product and specification
    //             $purchaseProduct = $purchase->purchaseProducts()
    //                 ->where('product_id', $productModel->id)
    //                 ->where('specification_id', $specification->id)
    //                 ->first();

    //             if ($purchaseProduct) {
    //                 $purchaseProduct->update(['qty' + $product['qty']]);
    //             } else {
    //                 $this->createNewPurchaseProductWithSpecification($product, $purchase, $productModel, $specification);
    //             }

    //             $specification->update(['qty' + $product['qty']]);
    //         } else {
    //             // Create new specification if not found
    //             $newSpecification = Specification::create([
    //                 'product_id' => $productModel->id,
    //                 'specs_value' => $product['specs_value'],
    //                 'purchase_price' => $product['purchase_price'],
    //                 'qty' => $product['qty'],
    //                 'sale_price' => $product['sale_price'],
    //                 'status' => $product['status'],
    //             ]);

    //             $this->createNewPurchaseProductWithSpecification($product, $purchase, $productModel, $newSpecification);
    //         }
    //     } else {
    //         // Create new product and specification
    //         $newProduct = Product::create([
    //             'name' => $product['product_name'],
    //             'category_id' => 1, // Default to 1 if none exists
    //             'brand_id' => 1,
    //         ]);
    //         $newSpecification = Specification::create([
    //             'product_id' => $newProduct->id,
    //             'specs_value' => $product['specs_value'],
    //             'purchase_price' => $product['purchase_price'],
    //             'qty' => $product['qty'],
    //             'sale_price' => $product['sale_price'],
    //             'status' => $product['status'],
    //         ]);

    //         $this->createNewPurchaseProductWithSpecification($product, $purchase, $newProduct, $newSpecification);
    //     }
    // }

    // private function updateOrCreatePurchaseProduct(array $product, Purchase $purchase)
    // {
    //     $purchaseProduct = $purchase->purchaseProducts()
    //         ->where('product_name', $product['product_name'])
    //         ->where('specs_value', $product['specs_value'])
    //         ->first();

    //     if ($purchaseProduct) {
    //         $purchaseProduct->update([
    //             'qty' => $product['qty'],
    //             'purchase_price' => $product['purchase_price'],
    //             'sale_price' => $product['sale_price'],
    //             'status' => $product['status'],
    //         ]);
    //     } else {
    //         $this->createNewPurchaseProduct($product, $purchase);
    //     }
    // }

    private function handleUpdateProduct(array $product, Purchase $purchase)
{
    $productModel = Product::where('name', $product['product_name'])->first();

    if ($productModel) {
        // Ensure category_id and brand_id are set to 1 if not already
        if (!$productModel->category_id) {
            $productModel->update(['category_id' => 1]);
        }
        if (!$productModel->brand_id) {
            $productModel->update(['brand_id' => 1]);
        }

        // Check if the specification exists
        $specification = Specification::where('product_id', $productModel->id)
            ->where('specs_value', $product['specs_value'])
            ->where('purchase_price', $product['purchase_price'])
            ->where('sale_price', $product['sale_price'])
            ->where('status', $product['status'])
            ->first();

        if ($specification) {
            // Check if the purchase product exists
            $purchaseProduct = $purchase->purchaseProducts()
                ->where('product_id', $productModel->id)
                ->where('specification_id', $specification->id)
                ->first();

            if ($purchaseProduct) {
                // Update the purchase product's quantity
                $purchaseProduct->update([
                    'qty' => $purchaseProduct->qty + $product['qty'],
                ]);
            } else {
                // Create a new purchase product if it doesn't exist
                $this->createNewPurchaseProductWithSpecification($product, $purchase, $productModel, $specification);
            }

            // Update the specification's quantity
            $specification->update([
                'qty' => $specification->qty + $product['qty'],
            ]);
        } else {
            // Create a new specification if it doesn't exist
            $newSpecification = Specification::create([
                'product_id' => $productModel->id,
                'specs_value' => $product['specs_value'],
                'purchase_price' => $product['purchase_price'],
                'qty' => $product['qty'], // Set the initial qty here
                'sale_price' => $product['sale_price'],
                'status' => $product['status'],
            ]);

            // Create a new purchase product with the new specification
            $this->createNewPurchaseProductWithSpecification($product, $purchase, $productModel, $newSpecification);
        }
    } else {
        // Create a new product and specification if the product doesn't exist
        $newProduct = Product::create([
            'name' => $product['product_name'],
            'category_id' => 1, // Default to 1 if none exists
            'brand_id' => 1,
        ]);

        $newSpecification = Specification::create([
            'product_id' => $newProduct->id,
            'specs_value' => $product['specs_value'],
            'purchase_price' => $product['purchase_price'],
            'qty' => $product['qty'],
            'sale_price' => $product['sale_price'],
            'status' => $product['status'],
        ]);

        $this->createNewPurchaseProductWithSpecification($product, $purchase, $newProduct, $newSpecification);
    }
}



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $purchase = Purchase::findOrFail($id);

        if ($purchase->shipping_status !== 'recieved') {
            $purchase->delete();
            toastr()->success('Purchase deleted successfully!');
        } else {
            toastr()->warning('You cannot delete this purchase.');
        }
        return redirect()->route('purchases.index');
    }
}
