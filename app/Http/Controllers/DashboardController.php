<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboardPage()
    {
        return view('pages.dashboard.dashboard-page');
    }

    public function dashboardSummary(Request $req)
    {
        try {
            $userId = $req->header('id');
            $category = Category::where('user_id', $userId)->count();
            $product = Product::where('user_id', $userId)->count();
            $customer = Customer::where('user_id', $userId)->count();
            $invoice = Invoice::where('user_id', $userId)->count();
            $totalSale = Invoice::where('user_id', $userId)->sum('total');
            $totalVat = Invoice::where('user_id', $userId)->sum('vat');
            $totalCollection = Invoice::where('user_id', $userId)->sum('payable');

            return response()->json([
                'success' => true,
                'summary' => [
                    'category' => $category,
                    'product' => $product,
                    'customer' => $customer,
                    'invoice' => $invoice,
                    'totalSale' => $totalSale,
                    'totalVat' => $totalVat,
                    'totalCollection' => $totalCollection,
                ]
            ]);
        } catch (\Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage()
            ]);
        }
    }
}
