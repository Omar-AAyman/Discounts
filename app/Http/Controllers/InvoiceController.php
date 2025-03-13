<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function subscriptions(Request $request)
    {
        $invoices = Invoice::where('type', 'subscription')
            ->when($request->status, fn($query) => $query->where('status', $request->status))
            ->when($request->date_from && $request->date_to, fn($query) => $query->whereBetween('created_at', [$request->date_from, $request->date_to]))
            ->get();

        return view('invoices.subscriptions', compact('invoices'));
    }

    public function products(Request $request)
    {
        $invoices = Invoice::where('type', 'products')
            ->when($request->status, fn($query) => $query->where('status', $request->status))
            ->when($request->date_from && $request->date_to, fn($query) => $query->whereBetween('created_at', [$request->date_from, $request->date_to]))
           ->get();

        return view('invoices.products', compact('invoices'));
    }

    public function show(Invoice $invoice)
    {
        return view('invoices.show', compact('invoice'));
    }
}
