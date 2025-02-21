<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::withCount('products')->paginate(10);
        
        return Inertia::render('Brands/Index', [
            'brands' => $brands
        ]);
    }
    
    public function create()
    {
        return Inertia::render('Brands/Create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:brands',
            'description' => 'nullable|string',
        ]);
        
        Brand::create($validated);
        
        return redirect()->route('brands.index')
            ->with('success', 'Brand created successfully.');
    }
    
    public function edit(Brand $brand)
    {
        return Inertia::render('Brands/Edit', [
            'brand' => $brand
        ]);
    }
    
    public function update(Request $request, Brand $brand)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $brand->id,
            'description' => 'nullable|string',
        ]);
        
        $brand->update($validated);
        
        return redirect()->route('brands.index')
            ->with('success', 'Brand updated successfully.');
    }
    
    public function destroy(Brand $brand)
    {
        $brand->delete();
        
        return redirect()->route('brands.index')
            ->with('success', 'Brand deleted successfully.');
    }
}