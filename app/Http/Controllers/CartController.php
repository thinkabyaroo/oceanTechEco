<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartResource;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware("auth:sanctum");
    }

    public function index()
    {
        $carts = CartResource::collection(Cart::all());
        return response()->json([
            "message"=>"success",
            'data'=>$carts,
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $cart=new Cart();
        $cart->id = $request->id;
        $cart->name = $request->name;
        $cart->amount = $request->amount;
        $cart->quantity = $request->quantity;
        $cart->user_id = Auth::id();
        $cart->product_id = $request->product_id;
        if($request->hasFile('photo')){
            $newName="photo".uniqid().".".$request->file('photo')->extension();
            $request->file('photo')->storeAs('public/photo',$newName);
            $cart->photo =$newName;
        }
        $cart->save();
        return response()->json([
            "message"=>"success",
            'data'=>new CartResource($cart),
        ],200);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cart = Cart::find($id);
        if (is_null($cart)){
            return response()->json([
                "message"=>"not found"
            ],404);
        }
        if (Gate::denies('view',$cart)){
            return response()->json([
                "message"=>"forbidden"
            ],403);
        }
        return response()->json([
            "message"=>"successful",
            "data"=>new CartResource($cart)
        ],200);
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
        $request->validate([
            'name'=>'nullable|min:3',
            'amount'=>'nullable',
            'quantity'=>'nullable',
            'product_id'=>'nullable',
            'photo'=>'nullable|file|mimes:jpeg,png|max:2000'
        ]);
        $cart = Cart::find($id);
        if (is_null($cart)){
            return response()->json([
                "message"=>"not found"
            ],404);
        }
        if (isset($request->name)){
            $cart->name = $request->name;
        }
        if (isset($request->amount)){
            $cart->amount = $request->amount;
        }
        if (isset($request->quantity)){
            $cart->quantity =$request->quantity;
        }
        if (isset($request->product_id)){
            $cart->product_id = $request->product_id;
        }
        if ($request->hasFile("photo")){
            Storage::delete('public/photo'.$cart->photo);
            $newName="photo_".uniqid().".".$request->file('photo')->extension();
            $request->file("photo")->storeAs('public/photo',$newName);
            $cart->photo = $newName;
        }
        if (Gate::denies('update',$cart)){
            return response()->json([
                "message"=>"forbidden"
            ],403);
        }
        $cart->update();
        return response()->json([
            "message"=>"successful",
            "data"=>new CartResource($cart)
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cart = Cart::find($id);
        if (is_null($cart)){
            return response()->json([
                "message"=>"not found"
            ],404);
        }
        if (Gate::denies('delete',$cart)){
            return response()->json([
                "message"=>"forbidden"
            ],403);
        }
        $cart->delete();
        return response()->json([
            "message"=>"deleted"
        ],204);
    }
}
