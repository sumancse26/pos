<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public function productPage()
    {
        return view('pages.dashboard.product-page');
    }

    public function createProduct(Request $req)
    {
        try {
            $userId = $req->header('id');

            $image = $req->file('image');
            $file_name = $image->getClientOriginalName();
            $time = time();
            $image_name = $userId . $time . $file_name;
            $img_url = "uploads/" . $image_name;
            $image->move(public_path('uploads'), $image_name);


            Product::create([
                'user_id' => $userId,
                'name' => $req->input('name'),
                'price' => $req->input('price'),
                'unit' => $req->input('unit'),
                'img_url' => $img_url,
                'category_id' => $req->input('category_id'),
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Product created successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getProduct(Request $req)
    {
        try {
            $userId = $req->header('id');
            $productList = Product::where('user_id', $userId)->get();
            return response()->json([
                'success' => true,
                'productList' => $productList
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getProductById(Request $req)
    {
        try {
            $productId = $req->input('product_id');
            $product = Product::find($productId);
            return response()->json([
                'success' => true,
                'product' => $product
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updateProduct(Request $req)
    {
        try {
            $productId = $req->input('id');
            $userId = $req->header('id');
            if ($req->hasFile('image')) {
                $image = $req->file('image');
                $file_name = $image->getClientOriginalName();
                $time = time();
                $image_name = "{$userId}-{$time}-{$file_name}";
                $img_url = "uploads/{$image_name}";

                $image->move(public_path('uploads'), $image_name);


                $filePath = Product::where('id', $productId)
                    ->where('user_id', $userId)
                    ->first()->img_url;
                $delete = File::delete($filePath);

                Product::where('id', $productId)
                    ->where('user_id', $userId)
                    ->update([
                        'user_id' => $userId,
                        'name' => $req->input('name'),
                        'price' => $req->input('price'),
                        'img_url' => $img_url,
                        'unit' => $req->input('unit'),
                        'category_id' => $req->input('category_id'),
                    ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Product updated successfully',
                ]);
            } else {

                Product::where('id', $productId)
                    ->where('user_id', $userId)
                    ->update([
                        'name' => $req->input('name'),
                        'price' => $req->input('price'),
                        'unit' => $req->input('unit'),
                        'category_id' => $req->input('category_id'),
                    ]);
                return response()->json([
                    'success' => true,
                    'message' => 'Product updated successfully'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function deleteProduct(Request $req)
    {
        try {
            $productId = $req->input('id');
            $userId = $req->header('id');

            $img_url = Product::where('id', $productId)
                ->where('user_id', $userId)
                ->first()->img_url;
            $delete = File::delete($img_url);

            Product::where('id', $productId)
                ->where('user_id', $userId)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
