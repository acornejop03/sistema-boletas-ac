@extends('layouts.app')
@section('title', 'Reporte por Curso')
@section('breadcrumb')
    <li class="breadcrumb-item active">Por Curso</li>
@endsection
@section('content')
<h5 class="fw-bold mb-3"><i class="bi bi-book-half me-2"></i>Ingresos por Curso - {{ $year }}</h5>
<div class="card mb-3"><div class="card-body py-2">
    <form method="GET" class="row g-2">
        <div class="col-md-3">
            <select name="year" class="form-select form-select-sm">
                @for($y=now()->year;$y>=now()->year-3;$y--)
                <option value="{{ $y }}" {{ $year==$y?'selected':'' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>
        <div class="col-md-2"><button class="btn btn-sm btn-primary">Filtrar</button></div>
    </form>
</div></div>
<div class="card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead><tr><th>Curso</th><th class="text-end">Cobros</th><th class="text-end">Total</th></tr></thead>
            <tbody>
                @forelse($data as $curso => $vals)
                <tr>
                    <td class="fw-semibold">{{ $curso }}</td>
                    <td class="text-end">{{ $vals['cantidad'] }}</td>
                    <td class="text-end fw-bold text-success">S/ {{ number_format($vals['total'], 2) }}</td>
                </tr>
                @empty
                <tr><td colspan="3" class="text-center text-muted py-4">No hay datos</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
