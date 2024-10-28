<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'PT Sans', sans-serif;
        }

        @page {
            size: 2.8in 11in;
            margin-top: 0cm;
            margin-left: 0cm;
            margin-right: 0cm;
        }

        table {
            width: 100%;
        }

        tr {
            width: 100%;

        }

        h1 {
            text-align: center;
            vertical-align: middle;
        }

        #logo {
            width: 30%;
            text-align: center;
            -webkit-align-content: center;
            align-content: center;
            padding: 5px;
            margin: 2px;
            display: block;
            margin: 0 auto;
        }

        header {
            width: 100%;
            text-align: center;
            -webkit-align-content: center;
            align-content: center;
            vertical-align: middle;
        }

        .items thead {
            text-align: center;
        }

        .center-align {
            text-align: center;
        }

        .bill-details td {
            font-size: 12px;
        }

        .receipt {
            font-size: medium;
        }

        .items .heading {
            font-size: 12.5px;
            text-transform: uppercase;
            border-top:1px solid black;
            margin-bottom: 4px;
            border-bottom: 1px solid black;
            vertical-align: middle;
        }

        .items thead tr th:first-child,
        .items tbody tr td:first-child {
            width: 47%;
            min-width: 47%;
            max-width: 47%;
            word-break: break-all;
            text-align: left;
        }

        .items td {
            font-size: 12px;
            text-align: right;
            vertical-align: bottom;
        }

        .price::before {
            //content: "LKR. ";
            font-family: Arial;
            text-align: right;
        }

        .sum-up {
            text-align: right !important;
        }
        .total {
            font-size: 13px;
            
        }
        .total.text, .total.price {
            text-align: right;
        }
        .total.price::before {
            content: "LKR. "; 
        }
        .line {
            border-top:1px solid black !important;
        }
        .heading.rate {
            width: 20%;
        }
        .heading.amount {
            width: 25%;
        }
        .heading.qty {
            width: 5%
        }
        p {
            padding: 1px;
            margin: 0;
        }
        section, footer {
            font-size: 12px;
        }
    </style>
</head>

<body>
    <img src="{{ public_path('assets/logo.png') }}" alt="logo" id="logo" class="media">
    <table class="bill-details">
        <tbody>
            <tr>
                <td>Date: <span>{{ $sale->created_at->format('Y-m-d') }} </span></td>
                <td>Time: <span>{{ $sale->created_at->format('H:i:s') }}</span></td>
            </tr>
            <tr>
                <td>Bill: #<span>{{ $sale->id }}</span></td>
                <td>Billed By: <span>{{ $sale->cashier->fname }}</span></td>
            </tr><br>
            <tr>
                <th class="center-align" colspan="2"><span class="receipt">Sale Receipt</span></th>
            </tr>
        </tbody>
    </table>
    
    <table class="items">
        <thead>
            <tr>
                <th class="heading name">Item</th>
                <th class="heading rate" style="text-align: right;">Rate</th>
                <th class="heading qty">Qty</th>                
                <th class="heading amount" style="text-align: right;">Amount</th>
            </tr>
        </thead>
       @php 
        $sale_items = App\Models\SaleItem::where('sale_id', $sale->id)->get();
       @endphp
        <tbody>
            @foreach($sale_items as $sale_item)
            <tr>
                <td>{{ $sale_item->item->name }}</td>
                <td class="price">{{ $sale_item->item_price }}</td>
                <td>{{ $sale_item->qty }}</td>                
                <td class="price">{{ $sale_item->amount }}</td>
            </tr>
            @endforeach
            <br>
        </tbody>
    </table>
    <table>
    <tr>
        <td colspan="3" class="total text line">Subtotal</td>
        <td class="line total price">{{ $sale->total }}</td>
    </tr>
    <tr>
                <th colspan="3" class="total text" style="border-top:1px dashed black !important;">Total</th>
                <th class="total price" style="border-top:1px dashed black !important;">{{ $sale->total }}</th>
            </tr>
            <tr>
                <th colspan="3" class="total text">Cash</th>
                <th class="total price">{{ $sale->cash }}</th>
            </tr>
            <tr>
                <th colspan="3" class="total text" style="border-bottom:1px dashed black !important;">Change</th>
                <th class="total price" style="border-bottom:1px dashed black !important;">{{ $sale->change }}</th>
            </tr>
    </table><br>
    <section>
        <p style="text-align:center">
            Thank you for your visit!
        </p>
    </section>
    <footer style="text-align:center">
        <p>Bitware POS by Bitware Global Company</p>
        <p>www.bitware.global</p>
    </footer>
</body>

</html>