<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    function CategoryPage()
    {
        return view('pages.dashboard.category-page');
    }

    public function getCategory(Request $req)
    {
        try {
            $userId = $req->header('id');

            $categoryList = Category::where('user_id', $userId)->get();
            return response()->json([
                'success' => true,
                'categoryList' => $categoryList
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    public function getCategoryById(Request $req)
    {
        try {
            $userId = $req->header('id');
            $catId = $req->input('id');

            $category = Category::where('user_id', $userId)
                ->where('id', $catId)
                ->first();
            return response()->json([
                'success' => true,
                'category' => $category
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }


    public function createCategory(Request $req)
    {
        try {
            $userId = $req->header('id');
            $name = $req->input('name');
            Category::create(['user_id' => $userId, 'name' => $name]);

            return response()->json([
                'success' => true,
                'message' => 'Category created successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function deleteCategory(Request $req)
    {
        try {
            $categoryId = $req->input('id');
            $userId = $req->header('id');

            Category::where('id', $categoryId)
                ->where('user_id', $userId)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updateCategory(Request $req)
    {
        try {
            $categoryId = $req->input('id');
            $userId = $req->header('id');
            $name = $req->input('name');
            Category::where('id', $categoryId)
                ->where('user_id', $userId)
                ->update(['name' => $name]);
            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
