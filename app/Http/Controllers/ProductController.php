<?php

namespace App\Http\Controllers;

use App\Exports\ProductsExport;
use App\Imports\ProductsImport;
use App\Models\Brand;
use App\Models\Product;
use App\Services\RazorpayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    protected $razorpayService;
    
    public function __construct(RazorpayService $razorpayService)
    {
        $this->razorpayService = $razorpayService;
    }
    
    public function index()
    {
        $products = Product::with('brand')->paginate(12);
        
        return Inertia::render('Products/Index', [
            'products' => $products
        ]);
    }
    
    public function create()
    {
        $brands = Brand::all();
        
        return Inertia::render('Products/Create', [
            'brands' => $brands
        ]);
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'brand_id' => 'required|exists:brands,id',
            'image' => 'nullable|image|max:2048',
        ]);
        
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image_path'] = $path;
        }
        
        Product::create($validated);
        
        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }
    
    public function show(Product $product)
    {
        $product->load('brand');
        
        return Inertia::render('Products/Show', [
            'product' => $product,
            'razorpayKey' => config('services.razorpay.key_id')
        ]);
    }
    
    public function edit(Product $product)
    {
        $brands = Brand::all();
        
        return Inertia::render('Products/Edit', [
            'product' => $product,
            'brands' => $brands
        ]);
    }
    
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'brand_id' => 'required|exists:brands,id',
            'image' => 'nullable|image|max:2048',
        ]);
        
        if ($request->hasFile('image')) {
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            
            $path = $request->file('image')->store('products', 'public');
            $validated['image_path'] = $path;
        }
        
        $product->update($validated);
        
        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }
    
    public function destroy(Product $product)
    {
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }
        
        $product->delete();
        
        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
    
    public function importForm()
    {
        return Inertia::render('Products/Import');
    }
    
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);
        
        Excel::import(new ProductsImport, $request->file('file'));
        
        return redirect()->route('products.index')
            ->with('success', 'Products imported successfully.');
    }
    
    public function export()
    {
        return Excel::download(new ProductsExport, 'products.xlsx');
    }
    
    public function createPayment(Product $product)
    {
        $order = $this->razorpayService->createOrder($product);
        
        return response()->json([
            'id' => $order->id,
            'amount' => $order->amount,
            'currency' => $order->currency,
            'product' => $product,
        ]);
    }
    
    public function verifyPayment(Request $request)
    {
        $request->validate([
            'razorpay_payment_id' => 'required|string',
            'razorpay_order_id' => 'required|string',
            'razorpay_signature' => 'required|string',
        ]);
        
        $verified = $this->razorpayService->verifySignature(
            $request->razorpay_payment_id,
            $request->razorpay_order_id,
            $request->razorpay_signature
        );
        
        if ($verified) {
            return redirect()->route('payment.success');
        }
        
        return redirect()->route('payment.failed');
    }
}