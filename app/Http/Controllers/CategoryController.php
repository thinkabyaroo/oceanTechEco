<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories=Category::all();
        return response()->json($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryRequest $request)
    {
        $category=new Category();
        $category->title=$request->title;
        $category->description=$request->description;
        $category->save();
        return $category;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category=Category::find($id);
        if (is_null($category)){
           return response()->json(["message"=>"not found","status"=>404],404);
        }
        return response()->json($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCategoryRequest  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request, $id)
    {
        $category=Category::find($id);
        if (is_null($category)){
            return response()->json([
                'message'=>'not found',
                'status'=>404
            ],404);
        }
        if ($request->has('title')){
            $category->title=$request->title;
        }
        if ($request->has('description')){
            $category->description=$request->description;
        }
        $category->update();
        return response()->json($category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category=Category::find($id);
        if (is_null($category)){
            return response()->json(["message"=>"not found","status"=>404],404);
        }

        $category->delete();
        return response()->json([
            "message"=>"deleted",
            "status"=>204
        ],204);
    }
}
