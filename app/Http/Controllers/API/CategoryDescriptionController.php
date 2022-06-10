<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CategoryDescriptionController extends Controller
{
    public function topstore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:191',

        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        } else {
            $category = new Top_category;
            $category->name = $request->input('name');
            $category->parent_id = $request->input('parent_id');
            $category->save();
            return response()->json([
                'status' => 200,
                'message' => 'Category Added Successfuly'
            ]);
        }
    }
}
