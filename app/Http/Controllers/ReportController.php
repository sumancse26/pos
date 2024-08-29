<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function reportPAge()
    {
        return view('pages.dashboard.report-page');
    }
    public function salesReport(Request $req)
    {
        try {
            $userId = $req->header('id');
            $formDate = date('Y-m-d', strtotime($req->formDate));
            $toDate = date('Y-m-d', strtotime($req->toDate));

            $invoices = Invoice::where('user_id', $userId)
                ->whereBetween('created_at', [$formDate, $toDate])
                ->with('customer')
                ->get();

            $total = $invoices->sum('total');
            $vat = $invoices->sum('vat');
            $discount = $invoices->sum('discount');
            $payable = $invoices->sum('payable');

            $data = [
                'total' => $total,
                'vat' => $vat,
                'discount' => $discount,
                'payable' => $payable,
                'list' => $invoices,
                'FormDate' => $formDate,
                'ToDate' => $toDate
            ];
            $pdf = Pdf::loadView('report.SalesReport', $data);
            return $pdf->download('invoice.pdf');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
