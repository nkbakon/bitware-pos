@extends('layouts.app')
@section('bodycontent')
<h1 class="text-center font-bold mt-2 text-pink-600 uppercase">Bitware POS <br>
<div class="py-12 ml-4 md:ml-0">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg sm:px-24 lg:px-26">
            <div class="md:flex md:space-x-40 justify-center">
                <a href="{{ route('transactions.index') }}">
                  <div class="justify-center inline-flex bg-gray-200 rounded-2xl overflow-hidden shadow-lg" style="width:320px; height:128px;">
                    <div class="px-6 py-4 text-center">
                      <p class="text-gray-500">
                        Bank Balance
                      </p>
                      <div class="font-bold text-5xl text-gray-700">
                        ${{ number_format(App\Models\Bank::sum('balance'), 2) }}
                      </div>  
                      <span class="text-gray-500 text-sm">(in USD)</span>                        
                    </div>
                  </div>
                </a>
            </div><br>
            <h1 class="text-gray-500 text-lg font-semibold">This Month</h1><br>
            <div class="md:flex md:space-x-40">
                <a href="{{ route('transactions.index') }}">
                  <div class="justify-center inline-flex bg-gray-200 rounded-2xl overflow-hidden shadow-lg" style="width:320px; height:128px;">
                    <div class="px-6 py-4 text-center">                        
                      <p class="text-gray-500">
                        Income
                      </p>
                      <div class="font-bold text-5xl text-green-500">${{ number_format(App\Models\Transaction::whereMonth('created_at', now())->where('type', 1)->sum('amount'), 2) }}</div>
                      <span class="text-gray-500 text-sm">(in USD)</span>
                    </div>
                  </div>
                </a>
                <a href="{{ route('transactions.index') }}">
                  <div class="justify-center inline-flex bg-gray-200 rounded-2xl overflow-hidden shadow-lg" style="width:320px; height:128px;">
                    <div class="px-6 py-4 text-center">                      
                      <p class="text-gray-500">
                          Expense
                      </p>
                      <div class="font-bold text-5xl text-red-500">${{ number_format(App\Models\Transaction::whereMonth('created_at', now())->where('type', 2)->sum('amount'), 2) }}</div>
                      <span class="text-gray-500 text-sm">(in USD)</span>
                    </div>
                  </div>
                </a>
            </div><br>
            <div class="py-6">
                <div class="px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg sm:px-24 lg:px-26">
                        <div class="chart">
                            <canvas class="inline-flex" id="mnthincomeChart" width="400" height="300"></canvas>
                        </div>  
                    </div>
                </div>
            </div><br>
            <div class="py-6">
                <div class="px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg sm:px-24 lg:px-26">
                        <div class="chart">
                            <canvas class="inline-flex" id="mnthexpenseChart" width="400" height="300"></canvas>
                        </div>  
                    </div>
                </div>
            </div>
            <div class="py-6">
              <div class="sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg sm:px-24 lg:px-26">
                  <div class="chart">
                    <canvas id="chart-line" height="300" width="400"></canvas>
                  </div>
                </div>
              </div>
            </div><br>
        </div>        
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  <?php
    $incomeData = [];
    $expenseData = [];
    for ($month = 1; $month <= 12; $month++) {
        $sum = App\Models\Transaction::whereMonth('created_at', $month)->where('type', 1)->sum('amount');
        array_push($incomeData, $sum);
        $expenseSum = App\Models\Transaction::whereMonth('created_at', $month)->where('type', 2)->sum('amount');
        array_push($expenseData, $expenseSum);
    }
  ?>

  var ctx = document.getElementById("mnthincomeChart").getContext("2d");

  new Chart(ctx, {
    type: "bar",
    data: {
      labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
      datasets: [
        {
          label: "# income in usd",
          tension: 0.4,
          borderWidth: 0,
          borderRadius: 4,
          borderSkipped: false,
          backgroundColor: "#16a34a",
          data: <?php echo json_encode($incomeData); ?>,
          maxBarThickness: 15,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false,
        },
      },
      interaction: {
        intersect: false,
        mode: "index",
      },
      scales: {
        y: {
          grid: {
            drawBorder: true,
            display: true,
            drawOnChartArea: true,
            drawTicks: true,
          },
          ticks: {
            suggestedMin: 0,
            suggestedMax: 600,
            beginAtZero: true,
            padding: 15,
            font: {
              size: 14,
              family: "Open Sans",
              style: "normal",
              lineHeight: 2,
            },
            color: "#b2b9bf",
          },
        },
        x: {
          grid: {
            drawBorder: false,
            display: false,
            drawOnChartArea: false,
            drawTicks: false,
          },
          ticks: {
            display: true,
            color: "#b2b9bf",
            padding: 20,
            font: {
              size: 11,
              family: "Open Sans",
              style: "normal",
              lineHeight: 2,
            },
          },
        },
      },
    },
  });

  var ctx2 = document.getElementById("mnthexpenseChart").getContext("2d");

  new Chart(ctx2, {
    type: "bar",
    data: {
      labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
      datasets: [
        {
          label: "# expense in usd",
          tension: 0.4,
          borderWidth: 0,
          borderRadius: 4,
          borderSkipped: false,
          backgroundColor: "#dc2626",
          data: <?php echo json_encode($expenseData); ?>,
          maxBarThickness: 15,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false,
        },
      },
      interaction: {
        intersect: false,
        mode: "index",
      },
      scales: {
        y: {
          grid: {
            drawBorder: true,
            display: true,
            drawOnChartArea: true,
            drawTicks: true,
          },
          ticks: {
            suggestedMin: 0,
            suggestedMax: 600,
            beginAtZero: true,
            padding: 15,
            font: {
              size: 14,
              family: "Open Sans",
              style: "normal",
              lineHeight: 2,
            },
            color: "#b2b9bf",
          },
        },
        x: {
          grid: {
            drawBorder: false,
            display: false,
            drawOnChartArea: false,
            drawTicks: false,
          },
          ticks: {
            display: true,
            color: "#b2b9bf",
            padding: 20,
            font: {
              size: 11,
              family: "Open Sans",
              style: "normal",
              lineHeight: 2,
            },
          },
        },
      },
    },
  });

  var ctx5 = document.getElementById("chart-line").getContext("2d");

  var gradientStroke1 = ctx5.createLinearGradient(0, 0, 0, 400);
  gradientStroke1.addColorStop(1, "rgba(22, 163, 74, 0.2)");
  gradientStroke1.addColorStop(0.2, "rgba(22, 163, 74, 0.0)");
  gradientStroke1.addColorStop(0, "rgba(22, 163, 74, 0)");

  var gradientStroke2 = ctx5.createLinearGradient(0, 230, 0, 50);
  gradientStroke2.addColorStop(1, "rgba(220, 38, 38, 0.2)");
  gradientStroke2.addColorStop(0.2, "rgba(220, 38, 38, 0.0)");
  gradientStroke2.addColorStop(0, "rgba(220, 38, 38, 0)");

  new Chart(ctx5, {
    type: "line",
    data: {
      labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
      datasets: [
        {
          label: "# income in usd",
          tension: 0.4,
          borderWidth: 0,
          pointRadius: 0,
          borderColor: "#16a34a",
          borderWidth: 3,
          backgroundColor: gradientStroke1,
          fill: true,
          data: <?php echo json_encode($incomeData); ?>,
          maxBarThickness: 6,
        },
        {
          label: "# expense in usd",
          tension: 0.4,
          borderWidth: 0,
          pointRadius: 0,
          borderColor: "#dc2626",
          borderWidth: 3,
          backgroundColor: gradientStroke2,
          fill: true,
          data: <?php echo json_encode($expenseData); ?>,
          maxBarThickness: 6,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false,
        },
      },
      interaction: {
        intersect: false,
        mode: "index",
      },
      scales: {
        y: {
          grid: {
            drawBorder: false,
            display: true,
            drawOnChartArea: true,
            drawTicks: false,
            borderDash: [5, 5],
          },
          ticks: {
            display: true,
            padding: 10,
            color: "#b2b9bf",
            font: {
              size: 11,
              family: "Open Sans",
              style: "normal",
              lineHeight: 2,
            },
          },
        },
        x: {
          grid: {
            drawBorder: false,
            display: false,
            drawOnChartArea: false,
            drawTicks: false,
            borderDash: [5, 5],
          },
          ticks: {
            display: true,
            color: "#b2b9bf",
            padding: 20,
            font: {
              size: 11,
              family: "Open Sans",
              style: "normal",
              lineHeight: 2,
            },
          },
        },
      },
    },
  });
</script>
@endpush