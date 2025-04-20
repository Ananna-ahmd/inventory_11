<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function CategoryList(Request $request)
    {
        $user_id=$request->header('id');
        return Category::where('user_id','=',$user_id)->get();

    }

    /**
     * Show the form for creating a new resource.
     */
    public function createCategory(Request $request)
    {
        $user_id=$request->header('id');
        return Category::create([
            'name'=>$request->input('name'),
            'user_id'=>$user_id
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function CategoryDelete(Request $request)
    {
        $category_id=$request->input('id');
        $user_id=$request->header('id');
        return Category::where('id','=',$category_id)->where('user_id',$user_id)->delete();
    }

    /**
     * Display the specified resource.
     */
    public function categoryById(Request $request)
    {
        $category_id=$request->input('id');
        $user_id=$request->header('id');
        return Category::where('id','=',$category_id)->where('user_id',$user_id)->first();
    }


    /**
     * Update the specified resource in storage.
     */
    public function CategoryUpdate(Request $request, Category $category)
    {
        $category_id=$request->input('id');
        $user_id=$request->header('id');
        return Category::where('id','=',$category_id)->where('user_id',$user_id)->update([
            'name'=>$request->input('name')
        ]);
    }


}
