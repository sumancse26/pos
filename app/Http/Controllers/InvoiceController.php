<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function SalesPage()
    {
        print_r("11");
        return view('pages.dashboard.sale-page');
    }
    public function InvoicePage()
    {
        return view('pages.dashboard.invoice-page');
    }

    public function createInvoice(Request $req)
    {
        DB::beginTransaction();
        try {
            $userId = $req->header('id');
            $customerId = $req->input('customer_id');
            $total = $req->input('total');
            $discount = $req->input('discount');
            $vat = $req->input('vat');
            $payable = $req->input('payable');

            $invoice = Invoice::create([
                'user_id' => $userId,
                'customer_id' => $customerId,
                'total' => $total,
                'discount' => $discount,
                'vat' => $vat,
                'payable' => $payable,
            ]);

            $invoiceId = $invoice->id;

            #forEach loop for products
            $products = $req->input('products');

            foreach ($products as $product) {
                $invoiceProduct = InvoiceProduct::create([
                    'invoice_id' => $invoiceId,
                    'user_id' => $userId,
                    'product_id' => $product['product_id'],
                    'qty' => $product['qty'],
                    'sale_price' => $product['sale_price'],
                ]);
            }
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Invoice created successfully'
            ]);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Invoice Creation Failed'
            ]);
        }
    }

    public function getInvoice(Request $req)
    {
        $userId = $req->header('id');
        $invoice = Invoice::where('user_id', $userId)->with('customer')->get();
        return response()->json($invoice);
    }

    function invoiceDetails(Request $req)
    {
        $id = $req->header('id');
        $customerDetail = Customer::where('user_id', $id)
            ->where('id', $req->input('cus_id'))
            ->first();

        $invoiceTotal = Invoice::where('user_id', $id)
            ->where('id', $req->input('inv_id'))
            ->first();
        $invoiceProduct = InvoiceProduct::where('user_id', $id)
            ->where('invoice_id', $req->input('inv_id'))
            ->with('product')
            ->get();


        return response()->json([
            'customer' => $customerDetail,
            'invoice' => $invoiceTotal,
            'products' => $invoiceProduct
        ]);
    }

    public function deleteInvoice(Request $req)
    {
        DB::beginTransaction();
        try {
            $id = $req->header('id');

            $inv = Invoice::where('user_id', $id)->where('id', $req->input('inv_id'))->firstOrFail();
            $inv->details()->delete();
            $inv->delete();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Invoice deleted successfully'
            ]);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Invoice deletion failed'
            ]);
        }
    }
}
