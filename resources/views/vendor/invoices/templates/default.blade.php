<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ $invoice->name }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style type="text/css" media="screen">
        html {
            font-family: sans-serif;
            line-height: 1.15;
            margin: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            font-weight: 400;
            line-height: 1.5;
            color: #212529;
            text-align: left;
            background-color: #fff;
            font-size: 8px; /* Decrease font size */
            margin: 18pt; /* Reduce margins */
        }

        h4 {
            margin-top: 0;
            margin-bottom: 0.3rem; /* Reduce margin-bottom */
        }

        p {
            margin-top: 0;
            margin-bottom: 0.5rem; /* Reduce margin-bottom */
        }

        strong {
            font-weight: bolder;
        }

        img {
            vertical-align: middle;
            border-style: none;
        }

        table {
            border-collapse: collapse;
            width: 100%; /* Ensure tables take full width */
        }

        th, td {
            padding: 0.3rem; /* Reduce padding */
            vertical-align: top;
        }

        h4, .h4 {
            margin-bottom: 0.5rem;
            font-weight: 500;
            line-height: 1.2;
        }

        h4, .h4 {
            font-size: 1.2rem; /* Decrease header font size */
        }

        .table {
            margin-bottom: 0.5rem; /* Reduce margin-bottom */
            color: #212529;
        }

        .table th,
        .table td {
            padding: 0.5rem; /* Reduce padding */
            vertical-align: top;
        }

        .table.table-items td {
            border-top: 1px solid #dee2e6;
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 1px solid #dee2e6;
        }

        .mt-5 {
            margin-top: 1rem !important; /* Reduce top margin */
        }

        .pr-0,
        .px-0 {
            padding-right: 0 !important;
        }

        .pl-0,
        .px-0 {
            padding-left: 0 !important;
        }

        .text-right {
            text-align: right !important;
        }

        .text-center {
            text-align: center !important;
        }

        .text-uppercase {
            text-transform: uppercase !important;
        }
        .divider {
            border-top: 1px dashed #000;
            margin: 3mm 0;
        }

        * {
            font-family: "DejaVu Sans";
        }

        body, h1, h2, h3, h4, h5, h6, table, th, tr, td, p, div {
            line-height: 1.1;
        }

        .party-header {
            font-size: 1.2rem; /* Reduce font size */
            font-weight: 400;
        }

        .total-amount {
            font-size: 10px; /* Decrease font size */
            font-weight: 700;
        }

        .border-0 {
            border: none !important;
        }

        .cool-gray {
            color: #6B7280;
        }

        .signature {
            border-top: 1px solid #000;
            width: 50%;
            margin-top: 10px; /* Reduce margin-top */
        }

        .move-up {
            margin-top: -20px; /* Move up by 20px */
        }
    </style>
</head>
<body>
{{-- Header --}}
<table class="table mt-5">
    <tbody>
    <tr>
        <td class="border-0 pl-0" width="70%">
            <strong>Məlumat Bərpa Mərkəzi | Recovery.az</strong>

            <h4 class="text-uppercase">
                <strong>{{ $invoice->getSerialNumber() }} | {{ $invoice->getDate() }}</strong>
            </h4>

        </td>
        <td class="border-0 pr-0 text-right">
            @if($invoice->logo)
                <img src="{{ $invoice->getLogo() }}" alt="logo" height="50"> <!-- Reduce logo size -->
            @endif
        </td>
    </tr>
    </tbody>
</table>

<table class="table move-up"> <!-- Add move-up class -->
    <tbody>
    <tr>
        <td class="border-0 pl-0" width="50%">
            @if($invoice->status)
                <h4 class="text-uppercase cool-gray">
                    <strong>{{ $invoice->status }}</strong>
                </h4>
            @endif
        </td>
    </tr>
    </tbody>
</table>

{{-- Seller - Buyer --}}
<table class="table move-up"> <!-- Add move-up class -->
    <thead>
    <tr>
        <th class="border-0 pl-0 party-header " style="text-align: left" width="48.5%">
            İcraçı
        </th>
        <th class="border-0" width="3%"></th>
        <th class="border-0 pl-0 party-header"  style="text-align: left">
            Müştəri
        </th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td class="px-0">
            @if($invoice->seller->name)
                <p class="seller-name">
                    <strong>{{ $invoice->seller->name }}</strong>
                </p>
            @endif

            @if($invoice->seller->address)
                <p class="seller-address">
                    {{ __('invoices::invoice.address') }}: {{ $invoice->seller->address }}
                </p>
            @endif

            @if($invoice->seller->code)
                <p class="seller-code">
                    {{ __('invoices::invoice.code') }}: {{ $invoice->seller->code }}
                </p>
            @endif



            @if($invoice->seller->phone)
                <p class="seller-phone">
                    {{ __('invoices::invoice.phone') }}: {{ $invoice->seller->phone }}
                </p>
            @endif

            @foreach($invoice->seller->custom_fields as $key => $value)
                <p class="seller-custom-field">
                    {{ ucfirst($key) }}: {{ $value }}
                </p>
            @endforeach
        </td>
        <td class="border-0"></td>
        <td class="px-0">
            @if($invoice->buyer->name)
                <p class="buyer-name">
                    <strong>{{ $invoice->buyer->name }}</strong>
                </p>
            @endif

            @if($invoice->buyer->address)
                <p class="buyer-address">
                    {{ __('invoices::invoice.address') }}: {{ $invoice->buyer->address }}
                </p>
            @endif

            @if($invoice->buyer->code)
                <p class="buyer-code">
                    {{ __('invoices::invoice.code') }}: {{ $invoice->buyer->code }}
                </p>
            @endif


            @if($invoice->buyer->phone)
                <p class="buyer-phone">
                    {{ __('invoices::invoice.phone') }}: {{ $invoice->buyer->phone }}
                </p>
            @endif

            @foreach($invoice->buyer->custom_fields as $key => $value)
                <p class="buyer-custom-field">
                    {{ ucfirst($key) }}: {{ $value }}
                </p>
            @endforeach
        </td>
    </tr>
    </tbody>
</table>
@if($invoice->notes)
    <p class="move-up"> <!-- Add move-up class -->
        {!! $invoice->notes !!}
    </p>
@endif

{{-- Table --}}
<table class="table table-items">
    <thead>
    <tr>
        <th scope="col" class="border-0 pl-0">Açıqlama</th>
        @if($invoice->hasItemUnits)
            <th scope="col" class="text-center border-0">{{ __('invoices::invoice.units') }}</th>
        @endif
        <th scope="col" class="text-center border-0">Xidmət sayı</th>
        <th scope="col" class="text-right border-0">Xidmətin Dəyəri</th>
        @if($invoice->hasItemDiscount)
            <th scope="col" class="text-right border-0">{{ __('invoices::invoice.discount') }}</th>
        @endif
        @if($invoice->hasItemTax)
            <th scope="col" class="text-right border-0">{{ __('invoices::invoice.tax') }}</th>
        @endif
        <th scope="col" class="text-right border-0 pr-0">Məbləğ</th>
    </tr>
    </thead>
    <tbody>
    {{-- Items --}}
    @foreach($invoice->items as $item)
        <tr>
            <td class="pl-0">
                {{ $item->title }}

                @if($item->description)
                    <p class="cool-gray">{{ $item->description }}</p>
                @endif
            </td>
            @if($invoice->hasItemUnits)
                <td class="text-center">{{ $item->units }}</td>
            @endif
            <td class="text-center">{{ $item->quantity }}</td>
            <td class="text-right">
                {{ $invoice->formatCurrency($item->price_per_unit) }}
            </td>
            @if($invoice->hasItemDiscount)
                <td class="text-right">
                    {{ $invoice->formatCurrency($item->discount) }}
                </td>
            @endif
            @if($invoice->hasItemTax)
                <td class="text-right">
                    {{ $invoice->formatCurrency($item->tax) }}
                </td>
            @endif

            <td class="text-right pr-0">
                {{ $invoice->formatCurrency($item->sub_total_price) }}
            </td>
        </tr>
    @endforeach
    {{-- Summary --}}


    <tr>
        <td colspan="{{ $invoice->table_columns - 2 }}" class="border-0"></td>
        <td class="text-right pl-0">Ümumi Məbləğ</td>
        <td class="text-right pr-0 total-amount">
            {{ $invoice->formatCurrency($invoice->total_amount) }}
        </td>
    </tr>
    </tbody>
</table>

{{-- Müştərinin və mühəndisin imzası --}}
<table class="table mt-3">
    <tbody>
    <tr>
        <td class="border-0 pl-0" width="50%">
            Mühəndis:
            <div class="signature"></div>
        </td>
        <td class="border-0 pl-0" width="50%">
            Müştəri imza:
            <div class="signature"></div>
        </td>
    </tr>
    </tbody>
</table>

{{-- Footer --}}
{{--<p class="text-left">--}}
{{--    <strong>Qeyd</strong>:<br/>--}}
{{--    1.Məlumat Bərpa Mərkəzi  müştəri tərəfindənlövhələrin dəyişdirilməsi, proqram təminatının quraşdırılması və ya yaddaş daşıyıcılarının dəyişdirilməsi ilə bağlı cihazın yaddaşında mümkün olan məlumat itkisinə görə məsuliyyət daşımır.<br/>--}}
{{--    2.Təmir (istilik emalı) zamanı istifadəçi tərəfindən istismar şərtlərinin kobud şəkildə pozulması, keçirici maye ilə təmas izləri (korroziya) və ya mexaniki zədələnmə halında, müştəri cihazın mümkün tam və ya qismən funksional itirilməsi riskini öz üzərinə götürür.<br/>--}}
{{--    4.Cihazın pulsuz saxlanma müddəti onun texniki müayinəyə qəbul edildiyi tarixdən 60 gündür.<br/>--}}
{{--    5.Qəbz itirildikdə cihaz müştəriyə şəxsiyyət vəsiqəsi təqdim edildikdə verilir.<br/>--}}
{{--    Tel: (+99455) 594 90 14 Mob: (+99455) 783 00 33 Veb səhifə: www.recovery.az E-mail: info@recovery.az<br/>--}}
{{--    Ünvan: 10/12 Üzeyir Hacıbəyov, Sahil m/s, Xaqani bağı bağı.--}}
{{--</p>--}}

<div class="divider"></div>

<table class="table mt-5">
    <tbody>
    <tr>
        <td class="border-0 pl-0" width="70%">
            <strong>Məlumat Bərpa Mərkəzi | Recovery.az</strong>

            <h4 class="text-uppercase">
                <strong>{{ $invoice->getSerialNumber() }} | {{ $invoice->getDate() }}</strong>
            </h4>

        </td>
        <td class="border-0 pr-0 text-right">
            @if($invoice->logo)
                <img src="{{ $invoice->getLogo() }}" alt="logo" height="50"> <!-- Reduce logo size -->
            @endif
        </td>
    </tr>
    </tbody>
</table>

<table class="table move-up"> <!-- Add move-up class -->
    <tbody>
    <tr>
        <td class="border-0 pl-0" width="50%">
            @if($invoice->status)
                <h4 class="text-uppercase cool-gray">
                    <strong>{{ $invoice->status }}</strong>
                </h4>
            @endif
        </td>
    </tr>
    </tbody>
</table>

{{-- Seller - Buyer --}}
<table class="table move-up"> <!-- Add move-up class -->
    <thead>
    <tr>
        <th class="border-0 pl-0 party-header " style="text-align: left" width="48.5%">
            İcraçı
        </th>
        <th class="border-0" width="3%"></th>
        <th class="border-0 pl-0 party-header"  style="text-align: left">
            Müştəri
        </th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td class="px-0">
            @if($invoice->seller->name)
                <p class="seller-name">
                    <strong>{{ $invoice->seller->name }}</strong>
                </p>
            @endif

            @if($invoice->seller->address)
                <p class="seller-address">
                    {{ __('invoices::invoice.address') }}: {{ $invoice->seller->address }}
                </p>
            @endif

            @if($invoice->seller->code)
                <p class="seller-code">
                    {{ __('invoices::invoice.code') }}: {{ $invoice->seller->code }}
                </p>
            @endif



            @if($invoice->seller->phone)
                <p class="seller-phone">
                    {{ __('invoices::invoice.phone') }}: {{ $invoice->seller->phone }}
                </p>
            @endif

            @foreach($invoice->seller->custom_fields as $key => $value)
                <p class="seller-custom-field">
                    {{ ucfirst($key) }}: {{ $value }}
                </p>
            @endforeach
        </td>
        <td class="border-0"></td>
        <td class="px-0">
            @if($invoice->buyer->name)
                <p class="buyer-name">
                    <strong>{{ $invoice->buyer->name }}</strong>
                </p>
            @endif

            @if($invoice->buyer->address)
                <p class="buyer-address">
                    {{ __('invoices::invoice.address') }}: {{ $invoice->buyer->address }}
                </p>
            @endif

            @if($invoice->buyer->code)
                <p class="buyer-code">
                    {{ __('invoices::invoice.code') }}: {{ $invoice->buyer->code }}
                </p>
            @endif


            @if($invoice->buyer->phone)
                <p class="buyer-phone">
                    {{ __('invoices::invoice.phone') }}: {{ $invoice->buyer->phone }}
                </p>
            @endif

            @foreach($invoice->buyer->custom_fields as $key => $value)
                <p class="buyer-custom-field">
                    {{ ucfirst($key) }}: {{ $value }}
                </p>
            @endforeach
        </td>
    </tr>
    </tbody>
</table>
@if($invoice->notes)
    <p class="move-up"> <!-- Add move-up class -->
        {!! $invoice->notes !!}
    </p>
@endif

{{-- Table --}}
<table class="table table-items">
    <thead>
    <tr>
        <th scope="col" class="border-0 pl-0">Açıqlama</th>
        @if($invoice->hasItemUnits)
            <th scope="col" class="text-center border-0">{{ __('invoices::invoice.units') }}</th>
        @endif
        <th scope="col" class="text-center border-0">Xidmət sayı</th>
        <th scope="col" class="text-right border-0">Xidmətin Dəyəri</th>
        @if($invoice->hasItemDiscount)
            <th scope="col" class="text-right border-0">{{ __('invoices::invoice.discount') }}</th>
        @endif
        @if($invoice->hasItemTax)
            <th scope="col" class="text-right border-0">{{ __('invoices::invoice.tax') }}</th>
        @endif
        <th scope="col" class="text-right border-0 pr-0">Məbləğ</th>
    </tr>
    </thead>
    <tbody>
    {{-- Items --}}
    @foreach($invoice->items as $item)
        <tr>
            <td class="pl-0">
                {{ $item->title }}

                @if($item->description)
                    <p class="cool-gray">{{ $item->description }}</p>
                @endif
            </td>
            @if($invoice->hasItemUnits)
                <td class="text-center">{{ $item->units }}</td>
            @endif
            <td class="text-center">{{ $item->quantity }}</td>
            <td class="text-right">
                {{ $invoice->formatCurrency($item->price_per_unit) }}
            </td>
            @if($invoice->hasItemDiscount)
                <td class="text-right">
                    {{ $invoice->formatCurrency($item->discount) }}
                </td>
            @endif
            @if($invoice->hasItemTax)
                <td class="text-right">
                    {{ $invoice->formatCurrency($item->tax) }}
                </td>
            @endif

            <td class="text-right pr-0">
                {{ $invoice->formatCurrency($item->sub_total_price) }}
            </td>
        </tr>
    @endforeach
    {{-- Summary --}}


    <tr>
        <td colspan="{{ $invoice->table_columns - 2 }}" class="border-0"></td>
        <td class="text-right pl-0">Ümumi Məbləğ</td>
        <td class="text-right pr-0 total-amount">
            {{ $invoice->formatCurrency($invoice->total_amount) }}
        </td>
    </tr>
    </tbody>
</table>

{{-- Müştərinin və mühəndisin imzası --}}
<table class="table mt-5">
    <tbody>
    <tr>
        <td class="border-0 pl-0" width="50%">
            Mühəndis:
            <div class="signature"></div>
        </td>
        <td class="border-0 pl-0" width="50%">
            Müştəri imza:
            <div class="signature"></div>
        </td>
    </tr>
    </tbody>
</table>

{{-- Footer --}}
<p class="text-left">
    <strong>Qeyd</strong>:<br/>
    1.Məlumat Bərpa Mərkəzi  müştəri tərəfindənlövhələrin dəyişdirilməsi, proqram təminatının quraşdırılması və ya yaddaş daşıyıcılarının dəyişdirilməsi ilə bağlı cihazın yaddaşında mümkün olan məlumat itkisinə görə məsuliyyət daşımır.<br/>
    2.Təmir (istilik emalı) zamanı istifadəçi tərəfindən istismar şərtlərinin kobud şəkildə pozulması, keçirici maye ilə təmas izləri (korroziya) və ya mexaniki zədələnmə halında, müştəri cihazın mümkün tam və ya qismən funksional itirilməsi riskini öz üzərinə götürür.<br/>
    4.Cihazın pulsuz saxlanma müddəti onun texniki müayinəyə qəbul edildiyi tarixdən 60 gündür.<br/>
    5.Qəbz itirildikdə cihaz müştəriyə şəxsiyyət vəsiqəsi təqdim edildikdə verilir.<br/>
    Tel: (+99455) 594 90 14 Mob: (+99455) 783 00 33 Veb səhifə: www.recovery.az E-mail: info@recovery.az<br/>
    Ünvan: 10/12 Üzeyir Hacıbəyov, Sahil m/s, Xaqani bağı bağı.
</p>
<script type="text/php">
    if (isset($pdf) && $PAGE_COUNT > 1) {
        $text = "{{ __('invoices::invoice.page') }} {PAGE_NUM} / {PAGE_COUNT}";
            $size = 8; /* Decrease font size */
            $font = $fontMetrics->getFont("Verdana");
            $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
            $x = ($pdf->get_width() - $width);
            $y = $pdf->get_height() - 35;
            $pdf->page_text($x, $y, $text, $font, $size);
        }
</script>
</body>
</html>
