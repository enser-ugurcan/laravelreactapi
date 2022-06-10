<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function addtoWishlist(Request $request)
    {
        if(auth('sanctum')->check())
        { 
            $user_id=auth('sanctum')->user()->id; 
            $product_id = $request->product_id;
            $product_qty = $request->product_qty;
            $productCheck = Product::where('id', $product_id)->first();
            if( $productCheck )
            {
                if(wishlist::where('product_id', $product_id)->where('user_id', $user_id)->exists())
                {
                        return response()->json([
                        'status'=>409,
                        'message'=>$productCheck->name. " Already Added to Wishlist",
                    ]);
                }
                else
                {
                    $cartitem = new wishlist;
                    $cartitem->user_id = $user_id;
                    $cartitem->product_id = $product_id;
                    $cartitem->product_qty = $product_qty;
                    $cartitem->save();
                    return response()->json([
                        'status'=>201,
                        'message'=>"Added to Wishlist",
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
            return response()->json([
                'status'=>401,
                'message'=>"Login to Add to Wishlist",
            ]);
        }
    }
    public function wishlistcart()
    {
       
        if(auth('sanctum')->check())
        { 
             $user_id = auth('sanctum')->user()->id;
             $wishlistitems = wishlist::where('user_id', $user_id)->get();
             return response()->json([
                'status'=>200,
                'wishlist'=>$wishlistitems,
            ]);    
        }
        else
        {
            return response()->json([
                'status'=>401,
                'message'=>"Login to View wishlist Data",
            ]);
        }
    }
    public function deletewishlistcart($wishlist_id)
    {
        if(auth('sanctum')->check())
        {
            $user_id = auth('sanctum')->user()->id;
            $Wishlistitem = Wishlist::where('id',$wishlist_id)->where('user_id',$user_id)->first();
            if($Wishlistitem)
            {
                $Wishlistitem->delete();
                return response()->json([
                    'status'=>200,
                    'message'=>"Wishlist Item Removed Successfully",
                ]); 
            }
            else
            {
                return response()->json([
                    'status'=>404,
                    'message'=>"Wishlist Item Not Found",
                ]); 
            }
        }
        else
        {
            return response()->json([
                'status'=>401,
                'message'=>"Login to Contiune",
            ]);
        }

    }
    public function addtoWishlistId(Request $request,$id)
    {
                $user_id=auth('sanctum')->user()->id;
                $check=DB::table('wishlist')->where('user_id',$user_id)->where('product_id',$id)->first();
                $product=DB::table('products')->where('id',$id)->first();
                if($check)
                {
                    return response()->json([
                        " Already Added to Wishlist",
                    ]);
                }
                else {
                    $data=array();
                    $data['product_id']=$id;
                    $data['user_id']=$user_id;
                    $data['product_qty']=1;
                    DB::table('wishlist')->insert($data);
                    
                    
                }
                $product_id =  $request->id;
                $product_qty=$request->product_qty;
                $data = Product::find($product_id);
               $add = Wishlist::add([
                   'user_id'=>$user_id,
                   'product_id'=>$data->product_id,
                   'product_qty'=>1
               ]);
                if($add)
                {
                  return response()->json([
                    'status'=>201,
                    'message'=>"Added to Wishlist",
                ]);   
                }
               
            
    }
}


