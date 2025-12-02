
<!DOCTYPE html>
<html>
<head>
<style>
table, td, th {
  border: 1px solid #ddd;
}

h2{
    font-size: 18px;    
}

p{
    font-size: 16px;    
}

table {
  border-collapse: collapse;
  font-size: 18px;
}

td{
    background-color: #f2f2f2;  
}

th{
    background-color: #1e40af;
    color: white;
}

.image-container {
    display: flex;
    justify-content: space-between;
}

.header-image-small {
    max-width: 20%;
    height: auto;
}
</style>
</head>
<body>
    <div class="image-container">
        <img src="{{ public_path('assets/logo.png') }}"  alt="Imagen 1" class="header-image-small">
    </div>
    <h2 style="text-align: center;">Invoice</h2>
    <p>Recored By <br>
        Name: {{ $transaction->addby->fname }} {{ $transaction->addby->lname }}<br>    
        Email: {{ $transaction->addby->email }}
    </p>
    <p>Date: {{ $transaction->date }}   
    </h2>
    <table style="width: 100%;">
        <tr>
            <th>ID</th>
            <th>Type</th>
            <th>Amount</th>>
        </tr>
        <tr>                                     
            <td>
                {{ $transaction->id }}
            </td>
            <td>
                @if($transaction->type == 2)
                Expense
                @else
                Income
                @endif
            </td>
            <td>
                {{ $transaction->amount }}
            </td>
        </tr>
    </table><br>
    <h2 style="text-align: right;">Total (USD): {{ $transaction->amount }}</h2><br>
    <h2 style="text-align: right;">.............................................</h2>
    <br>
    <p style="text-align: right;">Restaurant POS Company <br>
    </p>
</body>
</html>
