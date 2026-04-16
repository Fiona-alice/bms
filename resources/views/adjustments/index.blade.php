@extends('layouts.app')

@section('content')

<h5 class="mb-3">Stock Adjustments</h5>

<div class="col-md-3">
<div class="alert alert-danger">
Total Loss: <strong>UGX {{ number_format($totalLoss,2) }}</strong>
</div>
</div>

<div class="mb-3">
<a href="{{ route('adjustments.create') }}" class="btn btn-sm custom-pill-btn">
New Adjustment
</a>
</div>

<div class="card shadow-sm">
<div class="card-body">

<div style="max-height:400px; overflow-y:auto;">

<table class="table table-sm table-bordered table-striped">

<thead class="table-secondary sticky-top">
<tr>
<th>ID</th>
<th>Product</th>
<th>Type</th>
<th>Quantity</th>
<th>Cost</th>
<th>Total</th>
<th>Date</th>
<th>Reason</th>
</tr>
</thead>

<tbody>
@foreach($adjustments as $adj)

<tr>
<td>{{ $adj->id }}</td>
<td>{{ $adj->product->name }}</td>

<td>
<span class="badge 
{{ in_array($adj->type, ['loss','damage','theft']) ? 'bg-danger' : 'bg-success' }}">
{{ ucfirst($adj->type) }}
</span>
</td>

<td>{{ $adj->quantity }}</td>

<td>UGX {{ number_format($adj->cost_price,2) }}</td>

<td>UGX {{ number_format($adj->total_cost,2) }}</td>

<td>{{ $adj->date }}</td>

<td>{{ $adj->reason }}</td>

</tr>

@endforeach
</tbody>

</table>

</div>

</div>
</div>

@endsection