<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    function customerPage()
    {
        return view('pages.dashboard.customer-page');
    }

    public function getCustomer(Request $req)
    {
        try {
            $userId = $req->header('id');

            $customerList = Customer::where('user_id', $userId)->get();
            return response()->json([
                'success' => true,
                'customerList' => $customerList
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    public function getCustomerById(Request $req)
    {
        try {
            $userId = $req->header('id');
            $customerId = $req->input('id');

            $customer = Customer::where('user_id', $userId)
                ->where('id', $customerId)
                ->first();
            return response()->json([
                'success' => true,
                'customer' => $customer
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }


    public function createCustomer(Request $req)
    {
        try {
            $userId = $req->header('id');
            Customer::create([
                'user_id' => $userId,
                'name' => $req->input('name'),
                'email' => $req->input('email'),
                'mobile' => $req->input('mobile'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Customer created successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function deleteCustomer(Request $req)
    {
        try {
            $customerId = $req->input('id');
            $userId = $req->header('id');

            Customer::where('id', $customerId)
                ->where('user_id', $userId)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Customer deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updateCustomer(Request $req)
    {
        try {
            $customerId = $req->input('id');
            $userId = $req->header('id');

            Customer::where('id', $customerId)
                ->where('user_id', $userId)
                ->update([
                    'name' => $req->input('name'),
                    'email' => $req->input('email'),
                    'mobile' => $req->input('mobile'),
                ]);
            return response()->json([
                'success' => true,
                'message' => 'Customer updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
