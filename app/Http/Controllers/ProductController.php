<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $products=Product::get()->each(function ($contact){
//            if ($contact->photo === null){
//                $contact->photo = asset('user-default.jpg');
//            }else{
//                $contact->photo=asset('storage/photo/'.$contact->photo);
//            }
//        });
        $products=ProductResource::collection(Product::all());
        return response()->json([
            "message"=>"success",
            'data'=>$products,
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
//        return $request;
        $product=new Product();
        $product->title=$request->title;
        $product->description =$request->description;
        $product->brand_id=$request->brand_id;
        $product->category_id=$request->category_id;
        if ($request->hasFile('photo')){
            $newName="photo_".uniqid().".".$request->file('photo')->extension();
            $request->file('photo')->storeAs('public/photo',$newName);
            $product->photo=$newName;
        }
        $product->save();
        return response()->json([
            'message'=>'success',
            'data'=>new ProductResource($product),
        ],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product=Product::find($id);
        if (is_null($product)){
            return response()->json([
                'message'=>'not found'
            ],404);
        }
        return response()->json([
            'message'=>'success',
            'data'=>new ProductResource($product)
        ],200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductRequest  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request,$id)
    {
        $product=Product::find($id);
        if (is_null($product)){
            return response()->json([
                'message'=>'not found'
            ],404);
        }
        if (isset($request->title)){
            $product->title=$request->title;
        }
        if (isset($request->description)){
            $product->description=$request->description;
        }
        if (isset($request->brand_id)){
            $product->brand_id=$request->brand_id;
        }
        if (isset($request->category_id)){
            $product->category_id=$request->category_id;
        }

        if ($request->hasFile('photo')){
            Storage::delete('public/photo/'.$product->photo);
            $newName='photo_'.uniqid().'.'.$request->file('photo')->extension();
            $request->file('photo')->storeAs('public/photo',$newName);
            $product->photo=$newName;
        }
        $product->update();
        return response()->json($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product=Product::find($id);
        if (is_null($product)){
            return response()->json([
                    'message'=>'not found'
                ],404);
        }
        $product->delete();
        return response()->json([
            'message'=>'deleted'
        ],204);
    }
}
