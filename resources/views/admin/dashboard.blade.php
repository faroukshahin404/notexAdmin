@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Dashboard</h1>
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card"><div class="card-body"><strong>Total Tenants</strong><div class="h3">{{ $overview['total_tenants'] }}</div></div></div>
        </div>
        <div class="col-md-3">
            <div class="card"><div class="card-body"><strong>This Month</strong><div class="h3">{{ $overview['this_month'] }}</div></div></div>
        </div>
        <div class="col-md-3">
            <div class="card"><div class="card-body"><strong>Demo</strong><div class="h3">{{ $overview['demo'] }}</div></div></div>
        </div>
        <div class="col-md-3">
            <div class="card"><div class="card-body"><strong>Monthly Revenue</strong><div class="h3">${{ number_format($overview['monthly_revenue'], 2) }}</div></div></div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Tenants Per Month</div>
                <div class="card-body">
                    <canvas id="tenantsPerMonth"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Demo vs Paid</div>
                <div class="card-body">
                    <canvas id="demoPaid"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const months = {!! json_encode(array_keys($overview['tenants_per_month'])) !!};
const monthValues = {!! json_encode(array_values($overview['tenants_per_month'])) !!};

new Chart(document.getElementById('tenantsPerMonth'), {
  type: 'line',
  data: {
    labels: months,
    datasets: [{
      label: 'Tenants',
      data: monthValues,
      borderColor: '#36a2eb',
      fill: false
    }]
  },
});

new Chart(document.getElementById('demoPaid'), {
  type: 'pie',
  data: {
    labels: ['Demo', 'Paid'],
    datasets: [{
      data: [{{ $overview['demo'] }}, {{ $overview['paid'] }}],
      backgroundColor: ['#ffcd56', '#4bc0c0']
    }]
  },
});
</script>
@endsection


