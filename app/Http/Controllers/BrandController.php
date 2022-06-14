<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $brands=Brand::all();
        return response()->json($brands);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreBrandRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBrandRequest $request)
    {
        $brand=new Brand();
        $brand->title=$request->title;
        $brand->description=$request->description;
        $brand->user_id=Auth::id();
        $brand->save();
        return $brand;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $brand=Brand::find($id);
        if (is_null($brand)){
           return response()->json(["message"=>"Not found","status"=>404],404);
        }
        if (Gate::denies('view',$brand)){
            return response()->json([
                "message"=>'forbidden'
            ],403);
        }
        return response()->json($brand);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBrandRequest  $request
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBrandRequest $request,$id)
    {
        $brand=Brand::find($id);
        if (is_null($brand)){
            return response()->json([
                'message'=>'not found',
                'data'=>404
            ],404);
        }
        if (isset($request->title)){
            $brand->title=$request->title;
        }
        if (isset($request->description)){
            $brand->description=$request->description;
        }
        if (Gate::denies('update',$brand)){
            return response()->json([
                "message"=>'forbidden'
            ],403);
        }

        $brand->update();
        return response()->json($brand);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $brand=Brand::find($id);
        if (is_null($brand)){
            return response()->json(["message"=>"Not found","status"=>404],404);
        }
        if (Gate::denies('delete',$brand)){
            return response()->json([
                "message"=>'forbidden'
            ],403);
        }
        $brand->delete();
        return response()->json(["message"=>"deleted"],204);
    }
}
