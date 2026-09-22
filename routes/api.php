<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BarcodeController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CustomerAuthController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\DiscountController;
use App\Http\Controllers\Api\InventoryCountController;
use App\Http\Controllers\Api\InventoryCountItemController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductImageController;
use App\Http\Controllers\Api\ProductVariantController;
use App\Http\Controllers\Api\StockAlertController;
use App\Http\Controllers\Api\StockTransactionController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WarehouseController;
use App\Http\Controllers\Api\Admin\DiscountAdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductCollectionController;


Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
});

Route::middleware(['auth:sanctum', 'role:super_admin,admin1,admin2'])->group(function () {
    Route::apiResource('users', UserController::class);
    Route::patch('users/{user}/toggle-active', [UserController::class, 'toggleActive']);
});

Route::middleware(['auth:sanctum', 'role:super_admin,admin1'])->group(function () {

    // ─── Specific routes FIRST ─────────────────────────────
    Route::get('categories/tree', [CategoryController::class, 'tree']);

    // ─── apiResource registers /categories and /categories/{category} ───
    Route::apiResource('categories', CategoryController::class);

    // ─── Additional specific routes ────────────────────────
    Route::patch('categories/{category}/toggle-active', [CategoryController::class, 'toggleActive']);
    Route::post('categories/{id}/restore', [CategoryController::class, 'restore']);
});

// Super admin and admin1 have full CRUD

Route::middleware(['auth:sanctum', 'role:super_admin,admin1'])->group(function () {
    Route::apiResource('products', ProductController::class);
    Route::patch('products/{product_item}/toggle-active', [ProductController::class, 'toggleActive']);
    Route::post('products/{id}/restore', [ProductController::class, 'restore']);
});

// Admin2 can only view
Route::middleware(['auth:sanctum', 'role:super_admin,admin2'])->group(function () {
    Route::get('products', [ProductController::class, 'index']);
    Route::get('products/{product_item}', [ProductController::class, 'show']);
});

Route::middleware(['auth:sanctum', 'role:super_admin,admin1'])->group(function () {
    Route::apiResource('brands', BrandController::class);
    Route::patch('brands/{brand}/toggle-active', [BrandController::class, 'toggleActive']);
    Route::post('brands/{id}/restore', [BrandController::class, 'restore']);
});

Route::middleware(['auth:sanctum', 'role:super_admin,admin2'])->group(function () {
    Route::get('brands', [BrandController::class, 'index']);
    Route::get('brands/{brand}', [BrandController::class, 'show']);
});

// Super admin and admin1 have full CRUD
Route::middleware(['auth:sanctum', 'role:super_admin,admin1'])->group(function () {
    Route::apiResource('suppliers', SupplierController::class);
    Route::patch('suppliers/{supplier}/toggle-active', [SupplierController::class, 'toggleActive']);
    Route::patch('suppliers/{supplier}/toggle-preferred', [SupplierController::class, 'togglePreferred']);
    Route::post('suppliers/{id}/restore', [SupplierController::class, 'restore']);
});

// Admin2 can only view
Route::middleware(['auth:sanctum', 'role:super_admin,admin2'])->group(function () {
    Route::get('suppliers', [SupplierController::class, 'index']);
    Route::get('suppliers/{supplier}', [SupplierController::class, 'show']);
});

// Super admin and admin1 have full CRUD
Route::middleware(['auth:sanctum', 'role:super_admin,admin1'])->group(function () {
    Route::apiResource('warehouses', WarehouseController::class);
    Route::patch('warehouses/{warehouse}/toggle-active', [WarehouseController::class, 'toggleActive']);
    Route::patch('warehouses/{warehouse}/toggle-primary', [WarehouseController::class, 'togglePrimary']);
    Route::post('warehouses/{id}/restore', [WarehouseController::class, 'restore']);
});

// Admin2 can only view
Route::middleware(['auth:sanctum', 'role:super_admin,admin2'])->group(function () {
    Route::get('warehouses', [WarehouseController::class, 'index']);
    Route::get('warehouses/{warehouse}', [WarehouseController::class, 'show']);
});

// Super admin and admin1 have full CRUD
Route::prefix('products/{productId}/images')->group(function () {

    Route::get('/', [ProductImageController::class, 'index']);

    Route::post('/', [ProductImageController::class, 'store']);

    Route::delete(
        '{product_image}',
        [ProductImageController::class, 'destroy']
    );

    Route::patch(
        '{product_image}/primary',
        [ProductImageController::class, 'setPrimary']
    );

    Route::post(
        'reorder',
        [ProductImageController::class, 'reorder']
    );
});


// Admin2 can only view
Route::middleware(['auth:sanctum', 'role:super_admin,admin2'])->group(function () {
    Route::get('products/{productId}/images', [ProductImageController::class, 'index']);
    Route::get('product-images/{product_image}', [ProductImageController::class, 'show']);
});

Route::middleware(['auth:sanctum', 'role:super_admin,admin1'])->group(function () {

    Route::get(
        'products/{product}/variants',
        [ProductVariantController::class, 'index']
    );

    Route::post(
        'products/{product}/variants',
        [ProductVariantController::class, 'store']
    );

    Route::put(
        'products/{product}/variants/{productVariant}',
        [ProductVariantController::class, 'update']
    );

    Route::delete(
        'products/{product}/variants/{productVariant}',
        [ProductVariantController::class, 'destroy']
    );

    Route::patch(
        'products/{product}/variants/{productVariant}/toggle-active',
        [ProductVariantController::class, 'toggleActive']
    );

    Route::post(
        'products/{product}/variants/{productVariant}/restore',
        [ProductVariantController::class, 'restore']
    );
});


// Admin2 can view only
Route::middleware(['auth:sanctum', 'role:admin2'])->group(function () {
    Route::get('product-variants', [ProductVariantController::class, 'index']);
    Route::get('product-variants/{product_variant}', [ProductVariantController::class, 'show']);
});

// Super admin and admin1 have full CRUD
Route::middleware(['auth:sanctum', 'role:super_admin,admin1'])->group(function () {
    Route::apiResource('discounts', DiscountController::class);
    Route::patch('discounts/{discount}/toggle-active', [DiscountController::class, 'toggleActive']);
    Route::post('discounts/{id}/restore', [DiscountController::class, 'restore']);
});

// Admin2 can only view
Route::middleware(['auth:sanctum', 'role:super_admin,admin2'])->group(function () {
    Route::get('discounts', [DiscountController::class, 'index']);
    Route::get('discounts/{discount}', [DiscountController::class, 'show']);
});



// Super admin and admin1 have full CRUD
Route::middleware(['auth:sanctum', 'role:super_admin,admin1'])->group(function () {
    // ✅ SPECIFIC routes FIRST
    Route::get('barcodes/lookup', [BarcodeController::class, 'lookup']);
    Route::post('scan', [BarcodeController::class, 'scan']);

    // ✅ THEN the resource route (which contains a wildcard {barcode})
    Route::apiResource('barcodes', BarcodeController::class);
    Route::patch('barcodes/{barcode}/toggle-active', [BarcodeController::class, 'toggleActive']);
    Route::post('barcodes/{id}/restore', [BarcodeController::class, 'restore']);
});

// Admin2 can only view
Route::middleware(['auth:sanctum', 'role:super_admin,admin2'])->group(function () {
    Route::get('barcodes', [BarcodeController::class, 'index']);
    Route::get('barcodes/{barcode}', [BarcodeController::class, 'show']);
});


// Super admin and admin1 have full CRUD
Route::middleware(['auth:sanctum', 'role:super_admin,admin1'])->group(function () {
    Route::apiResource('stock-alerts', StockAlertController::class);
    Route::post('stock-alerts/{stock_alert}/resolve', [StockAlertController::class, 'resolve']);
    Route::post('stock-alerts/{id}/restore', [StockAlertController::class, 'restore']);
});

// Admin2 can only view
Route::middleware(['auth:sanctum', 'role:super_admin,admin2'])->group(function () {
    Route::get('stock-alerts', [StockAlertController::class, 'index']);
    Route::get('stock-alerts/{stock_alert}', [StockAlertController::class, 'show']);
});



// Super admin and admin1 have full CRUD
Route::middleware(['auth:sanctum', 'role:super_admin,admin1'])->group(function () {
    Route::apiResource('inventory-counts', InventoryCountController::class);
    Route::patch('inventory-counts/{inventory_count}/status', [InventoryCountController::class, 'changeStatus']);
    Route::post('inventory-counts/{id}/restore', [InventoryCountController::class, 'restore']);
});

// Admin2 can only view
Route::middleware(['auth:sanctum', 'role:super_admin,admin2'])->group(function () {
    Route::get('inventory-counts', [InventoryCountController::class, 'index']);
    Route::get('inventory-counts/{inventory_count}', [InventoryCountController::class, 'show']);
});



// Super admin and admin1 have full CRUD
Route::middleware(['auth:sanctum', 'role:super_admin,admin1'])->group(function () {
    Route::apiResource('inventory-count-items', InventoryCountItemController::class);
    Route::post('inventory-count-items/{inventory_count_item}/mark-counted', [InventoryCountItemController::class, 'markCounted']);
    Route::post('inventory-count-items/{inventory_count_item}/verify', [InventoryCountItemController::class, 'verify']);
    Route::post('inventory-count-items/{inventory_count_item}/adjust', [InventoryCountItemController::class, 'adjust']);
    Route::post('inventory-count-items/{id}/restore', [InventoryCountItemController::class, 'restore']);
});

// Admin2 can only view
Route::middleware(['auth:sanctum', 'role:super_admin,admin2'])->group(function () {
    Route::get('inventory-count-items', [InventoryCountItemController::class, 'index']);
    Route::get('inventory-count-items/{inventory_count_item}', [InventoryCountItemController::class, 'show']);
});



// Super admin and admin1 have full access (including manual adjustments)
Route::middleware(['auth:sanctum', 'role:super_admin,admin1'])->group(function () {
    Route::apiResource('stock-transactions', StockTransactionController::class)->except(['update']);
    Route::post('stock-transactions/{id}/restore', [StockTransactionController::class, 'restore']);
});

// Admin2 can only view
Route::middleware(['auth:sanctum', 'role:super_admin,admin2'])->group(function () {
    Route::get('stock-transactions', [StockTransactionController::class, 'index']);
    Route::get('stock-transactions/{stock_transaction}', [StockTransactionController::class, 'show']);
});



// Super admin and admin1 have full CRUD
Route::middleware(['auth:sanctum', 'role:super_admin,admin1'])->group(function () {
    Route::apiResource('customers', CustomerController::class);
    Route::patch('customers/{customer}/status', [CustomerController::class, 'changeStatus']);
    Route::post('customers/{id}/restore', [CustomerController::class, 'restore']);
});

// Admin2 can only view
Route::middleware(['auth:sanctum', 'role:admin2'])->group(function () {
    Route::get('customers', [CustomerController::class, 'index']);
    Route::get('customers/{customer}', [CustomerController::class, 'show']);
});

// Public customer authentication
Route::prefix('customers')->group(function () {
    Route::post('register', [CustomerAuthController::class, 'register']);
    Route::post('login', [CustomerAuthController::class, 'login']);

    // Protected customer routes (need customer token)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [CustomerAuthController::class, 'logout']);
        Route::get('me', [CustomerAuthController::class, 'me']);
        Route::put('me', [CustomerAuthController::class, 'updateProfile']);
    });
});


Route::post('/test-upload', function (Illuminate\Http\Request $request) {
    return response()->json([
        'has_file' => $request->hasFile('image'),
        'files' => $request->allFiles(),
        'post' => $_POST,
        'files_raw' => $_FILES,
        'content_type' => $request->header('Content-Type'),
    ]);
});



Route::prefix('collections')->group(function () {
    Route::get('new-arrivals', [ProductCollectionController::class, 'newArrivals']);
    Route::get('best-sellers', [ProductCollectionController::class, 'bestSellers']);
    Route::get('trending', [ProductCollectionController::class, 'trending']);
    Route::get('featured', [ProductCollectionController::class, 'featured']);
});



Route::prefix('admin')->middleware('auth:sanctum')->group(function () {
    Route::apiResource('discounts', DiscountAdminController::class);
});
