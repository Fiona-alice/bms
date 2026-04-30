@extends('layouts.app')
@section('content')
<div class="container mt-5">
<h4>New Purchase</h4>
<div class="card shadow-sm">
<div class="card-body">
<form action="{{ route('purchases.store') }}" method="POST">
@csrf

{{-- ── Core fields (unchanged) ──────────────────────── --}}
<div class="row">
  <div class="col-md-4 mb-3">
    <label class="form-label">Product</label>
    <select name="product_id" class="form-control" required>
      @foreach($products as $product)
        <option value="{{ $product->id }}" data-unit="{{ $product->unit->symbol ?? '' }}">{{ $product->name }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-4 mb-3">
    <label class="form-label">Quantity (<span id="unitLabel">--</span>)</label>
    <input type="number" name="quantity" id="quantity" class="form-control" required>
  </div>
  <div class="col-md-4 mb-3">
    <label class="form-label">Supplier Invoice Total</label>
    <input type="number" name="total_cost" id="total_cost" class="form-control" step="0.01" required>
  </div>
  <div class="col-md-4 mb-3">
    <label class="form-label">Date</label>
    <input type="date" name="date" class="form-control" required>
  </div>
</div>

{{-- ── Import / landed costs section ───────────────── --}}
<hr>
<div class="d-flex justify-content-between align-items-center mb-2">
  <h6 class="mb-0">Import Costs <small class="text-muted">(optional)</small></h6>
  <button type="button" class="btn btn-sm btn-outline-secondary" onclick="addCostRow()">+ Add Cost</button>
</div>

<div id="cost-rows">
  {{-- JS injects rows here --}}
</div>

{{-- ── Live summary ──────────────────────────────────── --}}
<div class="card bg-light mt-3 mb-3">
  <div class="card-body py-2">
    <div class="row text-center">
      <div class="col">
        <div class="text-muted small">Invoice total</div>
        <div class="fw-bold" id="s-base">0.00</div>
      </div>
      <div class="col">
        <div class="text-muted small">Import costs</div>
        <div class="fw-bold" id="s-extra">0.00</div>
      </div>
      <div class="col">
        <div class="text-muted small">Landed total</div>
        <div class="fw-bold" id="s-landed">0.00</div>
      </div>
      <div class="col">
        <div class="text-muted small">Supplier unit</div>
        <div class="fw-bold" id="s-unit">0.00</div>
      </div>
      <div class="col">
        <div class="text-muted small">Landed unit cost</div>
        <div class="fw-bold text-success" id="s-landed-unit">0.00</div>
      </div>
    </div>
  </div>
</div>

<button type="submit" class="btn btn-sm custom-pill-btn">Save</button>
<a href="{{ route('purchases.index') }}" class="btn btn-sm custom-pill-btn">Cancel</a>

</form>
</div>
</div>
</div>

<script>
document.getElementById('productSelect').addEventListener('change', function () {
    let selectedOption = this.options[this.selectedIndex];
    let unit = selectedOption.getAttribute('data-unit');

    document.getElementById('unitLabel').innerText = unit ? unit : '--';
});
</script>

<script>
const costTypes = [
  'freight', 'import_duty', 'vat',
  'clearing_forwarding', 'port_handling',
  'inland_transport', 'insurance', 'miscellaneous'
];

let rowIndex = 0;

function addCostRow() {
  const options = costTypes.map(t =>
    `<option value="${t}">${t.replace(/_/g,' ')}</option>`
  ).join('');

  const row = `
    <div class="row mb-2 cost-row align-items-center" data-index="${rowIndex}">
      <div class="col-md-3">
        <select name="costs[${rowIndex}][cost_type]" class="form-control form-control-sm">
          ${options}
        </select>
      </div>
      <div class="col-md-4">
        <input type="text" name="costs[${rowIndex}][description]"
               class="form-control form-control-sm" placeholder="Description (optional)">
      </div>
      <div class="col-md-3">
        <input type="number" name="costs[${rowIndex}][amount]"
               class="form-control form-control-sm cost-amount"
               placeholder="Amount" step="0.01" oninput="recalculate()">
      </div>
      <div class="col-md-2">
        <button type="button" class="btn btn-sm btn-outline-danger"
                onclick="this.closest('.cost-row').remove(); recalculate()">✕</button>
      </div>
    </div>`;

  document.getElementById('cost-rows').insertAdjacentHTML('beforeend', row);
  rowIndex++;
}

function recalculate() {
  const qty    = parseFloat(document.getElementById('quantity').value) || 0;
  const base   = parseFloat(document.getElementById('total_cost').value) || 0;
  const extras = [...document.querySelectorAll('.cost-amount')]
                   .reduce((s, el) => s + (parseFloat(el.value) || 0), 0);

  const landed     = base + extras;
  const unitCost   = qty > 0 ? base / qty : 0;
  const landedUnit = qty > 0 ? landed / qty : 0;

  document.getElementById('s-base').textContent        = base.toFixed(2);
  document.getElementById('s-extra').textContent       = extras.toFixed(2);
  document.getElementById('s-landed').textContent      = landed.toFixed(2);
  document.getElementById('s-unit').textContent        = unitCost.toFixed(4);
  document.getElementById('s-landed-unit').textContent = landedUnit.toFixed(4);
}

document.getElementById('quantity').addEventListener('input', recalculate);
document.getElementById('total_cost').addEventListener('input', recalculate);
</script>
@endsection