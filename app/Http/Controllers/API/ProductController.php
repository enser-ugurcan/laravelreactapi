<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Models\Produc_description;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'category_id'=>'required|max:191',
           // 'image'=>'required|image|mimes:jpeg,pjg,png|max:2048',

        ]);
        if($validator->fails())
        {
            return response()->json([
                'status'=>422,
                'errors'=>$validator->messages(),
            ]);
        }
        else {

            $product = new Product;
            if($request->hasFile('image'))
            {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename=time() .'.'.$extension;
                
                $product->image='uploads/product/'.$filename;
            }
            $product->category_id = $request->input('category_id');
            $product->slug = $request->input('slug');
            $product->name = $request->input('name');
            $product->selling_price = $request->input('selling_price');
            $product->original_price = $request->input('original_price');
            $product->qty = $request->input('qty');

            $product->brand = $request->input('brand');
            $product->color = $request->input('color');
            $product->size = $request->input('size');
            $product->featured = $request->input('featured') == true ? '1' : '0';
            $product->popular = $request->input('popular') == true ? '1' : '0';
            $product->status = $request->input('status') == true ? '1' : '0';
            $product->save();
    
             foreach ($request->category_desc_arr as $product_desc) {
                $desc = new Produc_description;
                $desc->language_id = $product_desc['language_id'];
                 $desc->description = $product_desc['description'];
                $desc->title = $product_desc['title'];
                $desc->product_id = $product->id;
                 $desc->save();
             }
            return response()->json([
                'status' => 200,
                'message' => "Product Added Successfuly",
            ]);
        }
     
    }

    public function store_description(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'language_id' => 'required|max:20',
            'description' => 'required',
            'meta_title' => 'required|max:191',

        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages(),
            ]);
        } else {
            $product = new Produc_description;
            $product->product_id = $request->input('product_id');
            $product->language_id = $request->input('language_id');
            $product->description = $request->input('description');
            $product->meta_title = $request->input('meta_title');
            $product->save();
            return response()->json([
                'status' => 200,
                'message' => "Product Added Successfuly",
            ]);
        }
    }
    public function index()
    {
        $products = Product::all();
        return response()->json([
            'status' => 200,
            'products' => $products
        ]);
    }
    public function edit($id)
    {
        $product = Product::with('ProductDescriptions')->find($id);
        if ($product) {
            return response()->json([
                'status' => 200,
                'product' => $product,
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Product Found',
            ]);
        }
    }
    public function delete($id)
    {
        $product = Product::with('ProductDescriptions')->find($id);
        if ($product) {
            $product->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Product Deleted Successfully',
            ]);
        } else {
            return response()->json([
                'status' => 200,
                'message' => 'No Product ID Found',
            ]);
        }
    }

    public function editDesc($id)
    {
        $product = DB::table('Produc_description')->where('id', $id)->first();
        if ($product) {
            return response()->json([
                'status' => 200,
                'product' => $product,
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No Product Found',
            ]);
        }
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            // 'category_id'=>'required|max:191',
            // 'slug'=>'required|max:191',
            // 'name'=>'required|max:191',
            // 'selling_price'=>'required|max:20',
            // 'original_price'=>'required|max:20',
            // 'qty'=>'required|max:30',
            // 'image'=>'required|image|mimes:jpeg,pjg,png|max:2048',
            // 'brand'=>'required|max:20',
            // 'language_id'=>'required|max:20',
            // 'color'=>'required|max:20',
            // 'text'=>'required|max:20',
            // 'description'=>'required|max:191',
            // 'meta_title'=>'required|max:191', 
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages(),
            ]);
        } else {
            $product = Product::find($id);
            if ($product) {
                $product->category_id = $request->input('category_id');
                $product->slug = $request->input('slug');
                $product->name = $request->input('name');
                $product->selling_price = $request->input('selling_price');
                $product->original_price = $request->input('original_price');
                $product->qty = $request->input('qty');
                if($request->hasFile('image'))
                {
                    $path=$product->image;
                    if(File::exists($path))
                    {
                        File::delete($path);
                    }
                    $file = $request->file('image');
                    $extension = $file->getClientOriginalExtension();
                    $filename=time() .'.'.$extension;
                    $file->move('uploads/product/', $filename);
                    $product->image='uploads/product/'.$filename;
                }
                $product->brand = $request->input('brand');
                $product->color = $request->input('color');
                $product->size = $request->input('size');
                $product->featured = $request->input('featured');
                $product->popular = $request->input('popular');
                $product->status = $request->input('status');
                    $product->update();
                
                    foreach ($request->category_desc_arr as $product_desc) {
                        $desc = Produc_description::where('language_id', "=", $product_desc["language_id"])->where("product_id", "=", $id)->first();
                        $desc->description =  $product_desc["description"];
                        $desc->save();
                    }
                return response()->json([
                    'status' => 200,
                    'message' => "Product Update Successfuly",
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => "Product Not Found",
                ]);
            }
        }
    }
    public function updateDescription(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'language_id' => 'required|max:20',
            'description' => 'required',
            'meta_title' => 'required|max:191',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages(),
            ]);
        } else {
            $product = Produc_description::find($id);
            if ($product) {
                $product->language_id = $request->input('language_id');
                $product->description = $request->input('description');
                $product->meta_title = $request->input('meta_title');
                $product->update();

                return response()->json([
                    'status' => 200,
                    'message' => "Product Update Successfuly",
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => "Product Not Found",
                ]);
            }
        }
    }
}
