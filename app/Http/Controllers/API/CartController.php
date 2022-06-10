<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Cart;

class CartController extends Controller
{
    public function addtocart(Request $request)
    {
        if(auth('sanctum')->check())
            { 
                $user_id=auth('sanctum')->user()->id; 
                $product_id = $request->product_id;
                $product_qty = $request->product_qty;

                $productCheck = Product::where('id', $product_id)->first();
                if( $productCheck )
                {
                    if(Cart::where('product_id', $product_id)->where('user_id', $user_id)->exists())
                    {
                            return response()->json([
                            'status'=>409,
                            'message'=>$productCheck->name. " Already Added to Cart",
                        ]);
                    }
                    else
                    {
                        $cartitem = new Cart;
                        $cartitem->user_id = $user_id;
                        $cartitem->product_id = $product_id;
                        $cartitem->product_qty = $product_qty;
                        $cartitem->save();
                        return response()->json([
                            'status'=>201,
                            'message'=>"Added to Cart",
                        ]);
                    }           
                }
                else
                { 
                    return response()->json([
                    'status'=>404,
                    'message'=>"Product Not Found",
                    ]);
                }
            }
        else
            {
                $user_id="unknowcustomer";
                $product_id = $request->product_id;
                $product_qty = $request->product_qty;

                $productCheck = Product::where('id', $product_id)->first();
                if( $productCheck )
                {
                    if(Cart::where('product_id', $product_id)->where('user_id', $user_id)->exists())
                    {
                            return response()->json([
                            'status'=>409,
                            'message'=>$productCheck->name. " Already Added to Cart",
                        ]);
                    }
                    else
                    {
                        $cartitem = new Cart;
                        $cartitem->user_id ="unknowcustomer";
                        $cartitem->product_id = $product_id;
                        $cartitem->product_qty = $product_qty;
                        $cartitem->save();
                        return response()->json([
                            'status'=>201,
                            'message'=>"Added to Cart",
                        ]);
                    }           
                }
            
            }  
             $user_id = auth('sanctum')->user()->id;
             $cartitems = Cart::where('user_id', $user_id)->get()->count();
             return response()->json([
                'status'=>200,
                'count'=>$cartitems
            ]); 

    }
    public function viewcart()
    {
        if(auth('sanctum')->check())
        { 
             $user_id = auth('sanctum')->user()->id;
             $cartitems = Cart::where('user_id', $user_id)->get();
             return response()->json([
                'status'=>200,
                'cart'=>$cartitems,
            ]);    
        }
        else
        {
            $user_id ="unknowcustomer";
            $cartitems = Cart::where('user_id',$user_id )->get();
             return response()->json([
                'status'=>200,
                'cart'=>$cartitems,
            ]);    
        }
    }
    public function updatequantity($cart_id , $scope)
    {   
        if(auth('sanctum')->check())
        {
            $user_id = auth('sanctum')->user()->id;
            $cartitem = Cart::where('id',$cart_id)->where('user_id',$user_id)->first();
            if($scope == "inc")
            {
                $cartitem->product_qty += 1 ;
            }
            else if($scope == "dec")
            {
                $cartitem->product_qty -= 1 ;
            }
            $cartitem->update();
            return response()->json([
                'status'=>200,
                'message'=>"Quantity Updated",
            ]);
            
        }
        else
        {
            $user_id ="unknowcustomer";
            $cartitem = Cart::where('id',$cart_id)->where('user_id',$user_id)->first();
            if($scope == "inc")
            {
                $cartitem->product_qty += 1 ;
            }
            else if($scope == "dec")
            {
                $cartitem->product_qty -= 1 ;
            }
            $cartitem->update();
            return response()->json([
                'status'=>200,
                'message'=>"Quantity Updated",
            ]);
            
        }
    }
    public function deleteCartitem($cart_id)
    {
        if(auth('sanctum')->check())
        {
            $user_id = auth('sanctum')->user()->id;
            $cartitem = Cart::where('id',$cart_id)->where('user_id',$user_id)->first();
            if($cartitem)
            {
                $cartitem->delete();
                return response()->json([
                    'status'=>200,
                    'message'=>"Cart Item Removed Successfully",
                ]); 
            }
            else
            {
                return response()->json([
                    'status'=>404,
                    'message'=>"Cart Item Not Found",
                ]); 
            }
        }
        else
        {
            $user_id = "unknowcustomer";
            $cartitem = Cart::where('id',$cart_id)->where('user_id',$user_id)->first();
            if($cartitem)
            {
                $cartitem->delete();
                return response()->json([
                    'status'=>200,
                    'message'=>"Cart Item Removed Successfully",
                ]); 
            }
            else
            {
                return response()->json([
                    'status'=>404,
                    'message'=>"Cart Item Not Found",
                ]); 
            }
        }
        $user_id = auth('sanctum')->user()->id;
        $cartitems = Cart::where('user_id', $user_id)->get()->count();
        return response()->json([
           'status'=>200,
           'count'=>$cartitems
       ]); 

    }
}