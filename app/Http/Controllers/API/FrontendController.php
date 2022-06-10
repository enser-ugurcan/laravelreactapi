<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Produc_description;
use App\Models\Order;
use Illuminate\Support\Carbon;
use App\Models\Contact;
use App\Models\Comment;
use App\Models\CategoryDescription;
use App\Models\Language;
use App\Models\Top_category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use ProtoneMedia\LaravelCrossEloquentSearch\Search;
use Illuminate\Support\Facades\DB;

class FrontendController extends Controller
{
    public function category(Request $request)
    {
        $langSlug = $request->lang;
        $langId = Language::where('name', $langSlug)->value('id');

        //$categories = Top_category::where('language_id', $langId)->get();
        //$category = Category::where('language_id', $langId)->get();
        // $categories = Category::whereNull('parent_id')
        //     ->where('language_id', $langId)
        //     ->with('childrenCategories')
        //     ->get();
        $categories = Category::whereNull('parent_id')
            ->with('CategoryDescriptions')
            ->with('childrenCategories')
            ->get();
        //$categories =  Category::with('sub_category')->where('language_id', $langId)->get();
        //dd($category);

        return response()->json([
            'status' => 200,
            'categories' => $categories
        ]);
    }
    public function product($id, Request $request)
    {
        $langSlug = $request->lang;
        $langId = Language::where('name', $langSlug)->value('id');

        $category = Category::where('id', $id)->wherE('status', '0')->first();

        if ($category) {
            $product = Product::where('category_id', $category->id)->where('status', '0')->get();

            $price = Product::where('category_id', $category->id)->orderBy('selling_price', 'desc')
                ->where('status', '0')
                ->get();
            $priceLow = Product::where('category_id', $category->id)->orderBy('selling_price', 'asc')
                ->where('status', '0')
                ->get();

            $parent_id = Category::whereNull('parent_id')->get();

            if ($product) {

                return response()->json([
                    'status' => 200,
                    'product_data' => [
                        'product' => $product,
                        'category' => $category,
                        'price' => $price,
                        'priceLow' => $priceLow,
                        'parent_id' => $parent_id,

                    ]
                ]);
            } else {
                return response()->json([
                    'status' => 400,
                    'message' => "No Such Product Available"
                ]);
            }
        } else {
            return response()->json([
                'status' => 404,
                'message' => "No Such Category Found"
            ]);
        }
    }
    public function PriceToLowToHigh($id, Request $request)
    {
        $langSlug = $request->lang;
        $langId = Language::where('name', $langSlug)->value('id');

        $category = Category::where('id', $id)->wherE('status', '0')->first();

        if ($category) {
            $price = Product::where('category_id', $category->id)->orderBy('selling_price', 'desc')
                ->where('status', '0')
                ->get();
            $priceLow = Product::where('category_id', $category->id)->orderBy('selling_price', 'asc')
                ->where('status', '0')
                ->get();

                return response()->json([
                    'status' => 200,
                        'category' => $category,
                        'price' => $price,
                        'priceLow' => $priceLow,
                ]);
        } 
    }
    public function viewproduct($id, $product_id)
    {
        $category = Category::where('id', $id)->where('status', '0')->first();
        if ($category) {
            $product = Product::with('ProductDescriptions')->where('category_id', $category->id)
                ->where('id', $product_id)
                ->where('status', '0')
                ->first();

            if ($product) {
                $products = Product::all();
                $productsss = Product::where('slug', 'like', '%' . $id . '%')->get();
                $productDescription = Produc_description::where('id', $product->id)->get()->first();


                $comment = Comment::where('product_id', $product->id)->get();
                $SumCommentRating = Comment::where('product_id', $product->id)->sum('rating');
                $SumCommentCount =  Comment::where('product_id', $product->id)->count('comment');
                if ($SumCommentCount == 0) {
                    $ValueOfCommentRating = 0;
                } else {
                    $ValueOfCommentRating =  $SumCommentRating / $SumCommentCount;
                }
                return response()->json([
                    'status' => 200,
                    'product' => $product,
                    'products' => $products,
                    'productDescription' => $productDescription,
                    'comment' => $comment,
                    'ValueOfCommentRating' => $ValueOfCommentRating,

                ]);
            } else {
                return response()->json([
                    'status' => 400,
                    'message' => "No Such Product Available"
                ]);
            }
        } else {
            return response()->json([
                'status' => 404,
                'message' => "No Such Category Found"
            ]);
        }
    }
    public function brand($category_slug, $product_name, $brand)
    {
        $category = Category::where('slug', $category_slug)->where('status', '0')->first();
        if ($category) {
            $product = Product::where('category_id', $category->id)
                ->where('name', $product_name)
                ->where('status', '0')
                ->first();

            if ($product) {
                $brandP = Product::where('id', $brands->id) - get();
                return response()->json([
                    'status' => 200,
                    'brands' => $brands,
                    'brandP' => $brandP
                ]);
            }
        }
    }
    public function search($key, Request $request)
    {
        $result = Product::where('name', 'like', "%$key%")->get();
        #Get minimum and maximum price to set in price filter range
        $price_max = Product::max('selling_price');
        $price_min = Product::min('selling_price');
        //dd('Minimum Price value in DB->'.$min_price,'Maximum Price value in DB->'.$max_price);

        #Get filter request parameters and set selected value in filter
        $filter_min_price = 450;
        $filter_max_price = 800;

        #Get products according to filter
        if ($filter_min_price && $filter_max_price) {

            $products = Product::whereBetween('selling_price', [$filter_min_price,  $filter_max_price])->get();
        } else {
            $products = Product::all();
        }

        return response()->json([
            'status' => 200,
            'products' => $products,
            'color' => $color,
            'min_price' => $price_min,
            'max_price' => $price_max,
            'result' => $result
        ]);
    }
    public function price(Request $request)
    {
        $query = Product::query();
        if ($sort = $request->input('sort')) {
            $query->orderBy('selling_price', $sort);
        }
        return $query->get();
        #Get minimum and maximum price to set in price filter range
        $min_price = Product::min('selling_price');
        $max_price = Product::max('selling_price');
        //dd('Minimum Price value in DB->'.$min_price,'Maximum Price value in DB->'.$max_price);

        #Get filter request parameters and set selected value in filter
        $filter_min_price = $request->min_price;
        $filter_max_price = $request->max_price;

        #Get products according to filter
        if ($filter_min_price && $filter_max_price) {
            if ($filter_min_price > 0 && $filter_max_price > 0) {
                $products = Product::select('name', 'image', 'original_price', 'selling_price')->whereBetween('selling_price', [$filter_min_price, $filter_max_price])->get();
            }
        }
        #Show default product list in Blade file
        else {
            $products = Product::select('name', 'image', 'original_price', 'selling_price')->get();
        }
        return response()->json([
            'status' => 200,
            'product' => $products,

        ]);
    }
    public function orders()
    {
        if (auth('sanctum')->check()) {
            $order_id = auth('sanctum')->user()->email;
            $orders = Order::where('email', $order_id)->get();
            return response()->json([
                'status' => 200,
                'orders' => $orders,
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => "No Such Product Available"
            ]);
        }
    }
    public function contact(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:191',
            'email' => 'required||email|max:255|',
            'message' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages(),
            ]);
        } else {
            $contact = new Contact;
            $contact->name = $request->input('name');
            $contact->email = $request->input('email');
            $contact->message = $request->input('message');
            $contact->save();
            return response()->json([
                'status' => 200,
                'message' => "Thanks For Message",
            ]);
        }
    }
    public function comment($category_id, $product_name, $id, Request $request)
    {
        $category = Category::where('id', $category_id)->wherE('status', '0')->first();
        if ($category) {
            $product = Product::where('category_id', $category->id)
                ->where('name', $product_name)
                ->where('status', '0')
                ->first();
            if ($product) {
                $product =  Product::find($id);
                $validator = Validator::make($request->all(), [
                    'name' => 'required|max:191',
                    'email' => 'required||email|max:255',
                    'rating' => 'required',
                    'comment' => 'required'
                ]);
                if ($validator->fails()) {
                    return response()->json([
                        'status' => 422,
                        'errors' => $validator->messages(),
                    ]);
                } else {
                    $comment = new Comment;
                    $user_id = auth('sanctum')->user()->id;
                    $comment->product_id = $id;
                    $comment->name = $request->input('name');
                    $comment->email = $request->input('email');
                    $comment->rating = $request->input('rating');
                    $comment->comment = $request->input('comment');
                    $comment->save();
                    return response()->json([
                        'status' => 200,
                        'message' => "Thanks For Comment",
                    ]);
                }
            }
        }
    }
}
