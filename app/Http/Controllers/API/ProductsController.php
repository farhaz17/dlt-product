<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\ProductAdded;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Product::query();

        // Check if a product name filter is provided
        if ($request->has('productName')) {
            $productName = $request->input('productName');
            $query->where('name', 'like', "%$productName%");
        }
    
         // Check if a user ID filter is provided
        if ($request->has('userId')) {
            $userId = $request->input('userId');
            $query->where('user_id', $userId);
        }
        
            // Retrieve the products with the user who added them
        $products = $query->with('user')->get();

    
        return response()->json($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'price' => 'required|numeric',
            'status' => 'required|in:active,inactive',
            'type' => 'required|in:item,service',
        ]);

        $product = new Product();
        $product->name = $validatedData['name'];
        $product->price = $validatedData['price'];
        $product->status = $validatedData['status'];
        $product->type = $validatedData['type'];
        $product->user_id = auth()->user()->id;

        $product->save();

        // Send an email notification to the user who added the product
        $user = User::find(auth()->user()->id);
        Mail::to($user->email)->send(new ProductAdded($product));

        return response()->json([
            'message' => 'Product added successfully',
            'data' => $product
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $product->load('user');
        
        return response()->json([
            'data' => $product
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'price' => 'required|numeric',
            'status' => 'required|in:active,inactive',
            'type' => 'required|in:item,service',
        ]);

        $product->name = $validatedData['name'];
        $product->price = $validatedData['price'];
        $product->status = $validatedData['status'];
        $product->type = $validatedData['type'];

        $product->save();

        return response()->json(['message' => 'Product updated successfully', 'product' => $product], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully'], 200);
    }
}
