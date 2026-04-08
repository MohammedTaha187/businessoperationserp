<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; line-height: 1.6; color: #333; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, .15); font-size: 16px; line-height: 24px; }
        .invoice-box table { width: 100%; line-height: inherit; text-align: right; }
        .invoice-box table td { padding: 5px; vertical-align: top; }
        .invoice-box table tr td:nth-child(2) { text-align: left; }
        .invoice-box table tr.top table td { padding-bottom: 20px; }
        .invoice-box table tr.top table td.title { font-size: 45px; line-height: 45px; color: #333; }
        .invoice-box table tr.information table td { padding-bottom: 40px; }
        .invoice-box table tr.heading td { background: #eee; border-bottom: 1px solid #ddd; font-weight: bold; }
        .invoice-box table tr.details td { padding-bottom: 20px; }
        .invoice-box table tr.item td { border-bottom: 1px solid #eee; }
        .invoice-box table tr.item.last td { border-bottom: none; }
        .invoice-box table tr.total td:nth-child(2) { border-top: 2px solid #eee; font-weight: bold; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">
                                <h2>{{ __('lang.Invoice') }}</h2>
                            </td>
                            <td>
                                {{ __('lang.Reference') }}: {{ $invoice->reference }}<br>
                                {{ __('lang.Date') }}: {{ $invoice->created_at->format('Y-m-d') }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>
                            <td>
                                {{ $invoice->company->getTranslation('name', 'ar') }}<br>
                                {{ $invoice->branch->name }}
                            </td>
                            <td>
                                {{ $invoice->customer->name ?? 'Guest' }}<br>
                                {{ $invoice->customer->email ?? '' }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="heading">
                <td>{{ __('lang.Item') }}</td>
                <td>{{ __('lang.Price') }}</td>
            </tr>
            @foreach($invoice->items as $item)
            <tr class="item">
                <td>{{ $item->product->getTranslation('name', 'ar') }} (x{{ $item->quantity }})</td>
                <td>{{ number_format($item->unit_price * $item->quantity, 2) }}</td>
            </tr>
            @endforeach
            <tr class="total">
                <td></td>
                <td>{{ __('lang.Total') }}: {{ number_format($invoice->total_amount, 2) }}</td>
            </tr>
        </table>
    </div>
</body>
</html>
