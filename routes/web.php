<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('categories/search', [CategoryController::class, 'search'])->name('categories.search');
Route::get('categories' , [CategoryController::class , 'index'])->name('categories.index');
Route::get('categories/create' , [CategoryController::class , 'create'])->name('categories.create');
Route::post('categories/create' , [CategoryController::class , 'store'])->name('categories.store');
Route::get('categories/{id}/edit' , [CategoryController::class , 'edit'])->name('categories.edit');
Route::put('categories/{id}/edit' , [CategoryController::class , 'update'])->name('categories.update');
Route::delete('categories/{id}' , [CategoryController::class , 'destroy'])->name('categories.destroy');

Route::get('brands/search' , [BrandController::class , 'search'])->name('brands.search');
Route::get('brands' , [BrandController::class , 'index'])->name('brands.index');
Route::get('brands/create' , [BrandController::class , 'create'])->name('brands.create');
Route::post('brands/create' , [BrandController::class , 'store'])->name('brands.store');
Route::get('brands/{id}/edit' , [BrandController::class , 'edit'])->name('brands.edit');
Route::put('brands/{id}/edit' , [BrandController::class , 'update'])->name('brands.update');
Route::delete('brands/{id}' , [BrandController::class , 'destroy'])->name('brands.destroy');

Route::get('products/search' , [ProductController::class , 'search'])->name('products.search');
Route::get('products' , [ProductController::class , 'index'])->name('products.index');
Route::get('products/create', [ProductController::class , 'create'])->name('products.create');
Route::post('products/create' , [ProductController::class , 'store'])->name('products.store');
Route::get('products/{id}/edit' , [ProductController::class , 'edit'])->name('products.edit');
Route::put('products/{id}/edit' , [ProductController::class , 'update'])->name('products.update');
Route::delete('products/{id}' , [ProductController::class , 'destroy'])->name('products.destroy');

Route::get('supplies/search' , [SupplierController::class , 'search'])->name('suppliers.search');
Route::get('suppliers' , [SupplierController::class , 'index'])->name('suppliers.index');
Route::get('suppliers/create' , [SupplierController::class , 'create'])->name('suppliers.create');
Route::post('suppliers/create' , [SupplierController::class , 'store'])->name('suppliers.store');
Route::get('suppliers/{id}/edit' , [SupplierController::class , 'edit'])->name('suppliers.edit');
Route::put('suppliers/{id}/edit' , [SupplierController::class , 'update'])->name('suppliers.update');
Route::delete('suppliers/{id}' , [SupplierController::class , 'destroy'])->name('suppliers.destroy');

Route::get('purchases/search' , [PurchaseController::class , 'search'])->name('purchases.search');
Route::get('purchases' , [PurchaseController::class , 'index'])->name('purchases.index');
Route::get('purchases/create' , [PurchaseController::class , 'create'])->name('purchases.create');
Route::post('purchases/create' , [PurchaseController::class , 'store'])->name('purchases.store');
Route::get('purchases/{id}/edit' , [PurchaseController::class , 'edit'])->name('purchases.edit');
Route::put('purchases/{id}/edit' , [PurchaseController::class , 'update'])->name('purchases.update');
Route::delete('purchases/{id}' , [PurchaseController::class , 'destroy'])->name('purchases.destroy');


Route::get('customers/search' , [CustomerController::class , 'search'])->name('customers.search');
Route::get('customers' , [CustomerController::class , 'index'])->name('customers.index');
Route::get('customers/create' , [CustomerController::class , 'create'])->name('customers.create');
Route::post('customers/create' , [CustomerController::class , 'store'])->name('customers.store');
Route::get('customers/{id}/edit' , [CustomerController::class , 'edit'])->name('customers.edit');
Route::put('customers/{id}/edit' , [CustomerController::class , 'update'])->name('customers.update');
Route::delete('customers/{id}' , [CustomerController::class , 'destroy'])->name('customers.destroy');
