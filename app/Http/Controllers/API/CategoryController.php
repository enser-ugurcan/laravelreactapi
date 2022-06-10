<?php

namespace App\Http\Controllers\API;

use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Models\CategoryDescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    // public function topindex()
    // {
    //     $category = Top_category::all();
    //     return response()->json([
    //         'status' => 200,
    //         'category' => $category,
    //     ]);
    // }
    // public function topstore(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required|max:191',

    //     ]);
    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => 400,
    //             'errors' => $validator->messages(),
    //         ]);
    //     } else {
    //         $category = new Top_category;
    //         $category->name = $request->input('name');
    //         $category->parent_id = $request->input('parent_id');
    //         $category->save();
    //         return response()->json([
    //             'status' => 200,
    //             'message' => 'Category Added Successfuly'
    //         ]);
    //     }
    // }
    // public function topedit($id)
    // {
    //     $category = Top_category::find($id);
    //     if ($category) {
    //         return response()->json([
    //             'status' => 200,
    //             'category' => $category
    //         ]);
    //     } else {
    //         return response()->json([
    //             'status' => 404,
    //             'message' => 'No Category Id Found',
    //         ]);
    //     }
    // }
    // public function topupdate(Request $request, $id)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required|max:255',

    //     ]);
    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => 422,
    //             'errors' => $validator->messages(),
    //         ]);
    //     } else {
    //         $category =  Top_category::find($id);
    //         if ($category) {
    //             $category->name = $request->input('name');
    //             $category->save();
    //             return response()->json([
    //                 'status' => 200,
    //                 'message' => 'Category Update Successfuly'
    //             ]);
    //         } else {
    //             return response()->json([
    //                 'status' => 404,
    //                 'message' => 'No Category ID Found',
    //             ]);
    //         }
    //     }
    // }
    // public function topdelete($id)
    // {
    //     $category = Top_category::find($id);
    //     if ($category) {
    //         $category->delete();
    //         return response()->json([
    //             'status' => 200,
    //             'message' => 'Category Deleted Successfully',
    //         ]);
    //     } else {
    //         return response()->json([
    //             'status' => 200,
    //             'message' => 'No Category ID Found',
    //         ]);
    //     }
    // }

    public function index()
    {
        $category = Category::all();
        return response()->json([
            'status' => 200,
            'category' => $category,
        ]);
    }
    public function store(Request $request)
    {
        $category = new Category;
        $category->parent_id = $request->input('parent_id');
        //$category->language_id = $request->input('language_id');
        //$category->slug = $request->input('slug');
        //$category->description = $request->input('descrip');
        $category->status = $request->input('status') ? '1' : '0';
        $category->save();

        foreach ($request->category_desc_arr as $cat_desc) {
            $desc = new CategoryDescription;
            $desc->language_id = $cat_desc['language_id'];
            $desc->title = $cat_desc['slug'];
            $desc->description = $cat_desc['descrip'];
            $desc->category_id = $category->id;
            $desc->save();
        }
        return response()->json([
            'status' => 200,
            'message' => 'Category Added Successfuly',
            // 'parent_idd'=>$parent_idd
        ]);
    }
      public function allCategory()
    {
        $category = CategoryDescription::where('language_id', 2)->get();
   //     $category_desc = Category::where('id', $category->id)->where('parent_id',NULL)->first();
        return response()->json([
            'status' => 200,
            'category' => $category,
        ]);
        dd($category);
    }


    public function edit($id)
    {
        $category = Category::find($id);
        if ($category) {
            return response()->json([
                'status' => 200,
                'category' => $category
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Category Id Found',
            ]);
        }
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'meta_title' => 'required|max:255',
            'slug' => 'required|max:255',
            'language_id' => 'required',

        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages(),
            ]);
        } else {
            $category =  Category::find($id);
            if ($category) {
                $category->meta_title = $request->input('meta_title');
                $category->parent_id = $request->input('parent_id');
                $category->language_id = $request->input('language_id');
                $category->slug = $request->input('slug');
                $category->description = $request->input('description');
                $category->status = $request->input('status') == true ? '1' : '0';
                $category->save();
               
                foreach ($request->category_desc_arr as $cat_desc) {
                    $desc = CategoryDescription::where('language_id', "=", $cat_desc["language_id"])->where("category_id", "=", $id)->first();
                    $desc->description =  $cat_desc["description"];
                    $desc->save();
                }
                return response()->json([
                    'status' => 200,
                    'message' => 'Category Update Successfuly'
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'No Category ID Found',
                ]);
            }
        }
    }
    public function delete($id)
    {
        $category = Category::find($id);
        if ($category) {
            $category->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Category Deleted Successfully',
            ]);
        } else {
            return response()->json([
                'status' => 200,
                'message' => 'No Category ID Found',
            ]);
        }
    }

}
