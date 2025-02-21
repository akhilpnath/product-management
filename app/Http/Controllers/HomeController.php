<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::with('brand')
            ->latest()
            ->take(8)
            ->get();
            
        return Inertia::render('Home', [
            'products' => $products,
        ]);
    }
    
    public function paymentSuccess()
    {
        return Inertia::render('Payment/Success');
    }
    
    public function paymentFailed()
    {
        return Inertia::render('Payment/Failed');
    }
}