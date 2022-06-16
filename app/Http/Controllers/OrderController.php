<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index()
    {
        $orders =Order::all();
        return response()->json($orders);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $order = new Order();
        $order->id = $request->id;
        $order->cart_id = $request->cart_id;
        $order->total_amount = $request->total_amount;
        $order->quantity = $request->quantity;
        $order->user_id = Auth::id();
        $order->order_date = $request->order_date;
        $order->save();
        return response()->json([
            "message"=>"successful",
            "data"=>$order
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
        $order = Order::find($id);
        if (is_null($order)){
            return response()->json([
                "message"=>"not found"
            ],404);
        }
        if (Gate::denies('view',$order)){
            return response()->json([
                "message"=>"forbidden"
            ],403);
        }
        return response()->json([
            "message"=>"successful",
            "data"=>$order
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
            'cart_id'=>"nullable",
            'total_amount'=>'nullable',
            'quantity'=>'nullable',
            'order_date'=>'nullable'
        ]);
        $order = Order::find($id);
        if (is_null($order)){
            return response()->json([
                "message"=>"not found"
            ],404);
        }
        if (isset($request->cart_id)){
            $order->cart_id = $request->cart_id;
        }
            if (isset($request->total_amount)){
            $order->total_amount = $request->total_amount;
        }
        if (isset($request->quantity)){
            $order->quantity = $request->quantity;
        }
        if (isset($request->order_date)){
            $order->order_date = $request->order_date;
        }
        if (Gate::denies('update',$order)){
            return response()->json([
                "message"=>"forbidden"
            ],403);
        }
        $order->update();
        return response()->json([
            "message"=>"updated",
            "data"=>$order
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
        $order = Order::find($id);
        if (is_null($order)){
            return response()->json([
                "message"=>"not found"
            ],404);
        }
        if (Gate::denies("delete",$order)){
            return response()->json([
                "message"=>"forbidden"
            ],403);
        }
        $order->delete();
        return response()->json([
            "message"=>"deleted"
        ],204);
    }
}
