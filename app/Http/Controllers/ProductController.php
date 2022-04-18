<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use File;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $getAllProducts = Product::where('user_id',Auth::id())->get();

        foreach($getAllProducts as $getAllProduct){
            $getAllProduct->imageUrl = asset('/profile_images/'.$getAllProduct->image);
        }
        return $this->sendResponse($getAllProducts, 'All products retrived successfully.');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'auther_name' => 'required',
            'name' => 'required',
            'description' => 'required',
            'amount' => 'required',
            'image' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]); 

        if ($validator->fails()) {          
            return $this->sendError('Validation Error.', $validator->errors());                     
        }  

        $profileImage = null;

        if ($request->file('image')) {
            $file = $request->file('image'); 
            $destinationPath = public_path('/profile_images/'); 
            $profileImage = date('YmdHis') . "." . $file->getClientOriginalExtension();
            $file->move($destinationPath, $profileImage);
        }

        $product = new Product();
        $product->user_id = Auth::id();
        $product->auther_name = $request->auther_name;
        $product->name = $request->name;
        $product->image = $profileImage;
        $product->description = $request->description; 
        $product->amount = $request->amount; 
        $product->save();
        $success['product'] =  $product;
        return $this->sendResponse($success, 'Product added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $product->imageUrl = asset('/profile_images/'.$product->image);
        return $this->sendResponse($product, 'Product retrived successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        $validator = Validator::make($request->all(),[ 
            'auther_name' => 'required',
            'name' => 'required',
            'description' => 'required',
            'amount' => 'required',
        ]); 
      
        if ($validator->fails()) {          
            return $this->sendError('Validation Error.', $validator->errors());                     
        }  

        if ($request->file('image')) {
            $usersImage = public_path("profile_images/{$product->image}");
            if (isset($product->image) && File::exists($usersImage)) { // unlink or remove previous image from folder
                unlink($usersImage);
            }
            $file = $request->file('image'); 
            $destinationPath = public_path('/profile_images/'); 
            $profileImage = date('YmdHis') . "." . $file->getClientOriginalExtension();
            $file->move($destinationPath, $profileImage);
            $product->image = $profileImage;
        }

        $product->auther_name = $request->auther_name;
        $product->name = $request->name;
        $product->description = $request->description;
        $product->amount = $request->amount;
        $product->save();

        return $this->sendResponse($product, 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return $this->sendResponse([],'Product deleted successfully.');
    }
}
