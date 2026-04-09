<?php

namespace App\Services\Api\V1;

use App\Models\Invoice;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;

class ReportingService
{
    /**
     * Export invoices to Excel.
     */
    public function exportInvoicesToExcel(Collection $invoices): string
    {
        // Simple collection export for demonstration
        // In a real app, we'd use a dedicated Export class
        return Excel::download(new class($invoices) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings
        {
            public function __construct(private Collection $collection) {}

            public function collection()
            {
                return $this->collection;
            }

            public function headings(): array
            {
                return ['ID', 'Reference', 'Customer ID', 'Branch ID', 'Total', 'Status', 'Created At'];
            }
        }, 'invoices_'.now()->format('Y-m-d').'.xlsx')->getFile()->getPathname();
    }

    /**
     * Export single invoice to PDF.
     */
    public function exportInvoiceToPdf(Invoice $invoice): string
    {
        $pdf = Pdf::loadView('reports.invoice', compact('invoice'));
        $path = storage_path('app/public/reports/invoice_'.$invoice->id.'.pdf');

        // Ensure directory exists
        if (! file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        $pdf->save($path);

        return asset('storage/reports/invoice_'.$invoice->id.'.pdf');
    }

    /**
     * Export inventory status to Excel.
     */
    public function exportInventoryStatus(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $products = Product::with('inventories')->get()->map(function ($product) {
            return [
                'name' => $product->getTranslation('name', 'ar'),
                'sku' => $product->sku,
                'price' => $product->price,
                'stock' => $product->inventories->sum('quantity'),
            ];
        });

        return Excel::download(new class($products) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings
        {
            public function __construct(private Collection $collection) {}

            public function collection()
            {
                return $this->collection;
            }

            public function headings(): array
            {
                return ['Product Name', 'SKU', 'Price', 'Total Stock'];
            }
        }, 'inventory_report_'.now()->format('Y-m-d').'.xlsx');
    }
}
