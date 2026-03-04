@extends('layouts.app')
@section('title', $student->nombre_completo)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('students.index') }}">Alumnos</a></li>
    <li class="breadcrumb-item active">{{ $student->codigo }}</li>
@endsection
@section('content')
<div class="row g-3">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body py-4">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center fw-bold text-white mb-3"
                     style="width:80px;height:80px;background:#2d5a9b;font-size:2rem">
                    {{ strtoupper(substr($student->apellido_paterno, 0, 1)) }}
                </div>
                <h5 class="mb-1">{{ $student->nombre_completo }}</h5>
                <code class="d-block mb-2">{{ $student->codigo }}</code>
                <span class="badge {{ $student->activo ? 'bg-success' : 'bg-secondary' }} mb-2">
                    {{ $student->activo ? 'Activo' : 'Inactivo' }}
                </span>
                <div class="small text-muted">
                    {{ $student->tipo_doc_nombre }}: {{ $student->numero_documento ?? '—' }}
                </div>
            </div>
        </div>
        @can('editar alumnos')
        <a href="{{ route('students.edit', $student) }}" class="btn btn-outline-primary w-100 mt-2">
            <i class="bi bi-pencil me-1"></i>Editar datos
        </a>
        @endcan
        @can('crear cobros')
        <a href="{{ route('payments.create', ['student_id' => $student->id]) }}" class="btn btn-success w-100 mt-2">
            <i class="bi bi-cash-coin me-1"></i>Registrar cobro
        </a>
        @endcan
    </div>
    <div class="col-md-9">
        <ul class="nav nav-tabs mb-3" id="studentTabs">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tab-datos">Datos</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-matriculas">Matrículas</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-pagos">Pagos</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-comprobantes">Comprobantes</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="tab-datos">
                <div class="card"><div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6"><strong class="text-muted small d-block">Nombre completo</strong>{{ $student->nombre_completo }}</div>
                        <div class="col-md-6"><strong class="text-muted small d-block">Fecha nacimiento</strong>{{ $student->fecha_nacimiento?->format('d/m/Y') ?? '—' }}</div>
                        <div class="col-md-6"><strong class="text-muted small d-block">Email</strong>{{ $student->email ?? '—' }}</div>
                        <div class="col-md-6"><strong class="text-muted small d-block">Teléfono</strong>{{ $student->telefono ?? '—' }}</div>
                        <div class="col-md-12"><strong class="text-muted small d-block">Dirección</strong>{{ $student->direccion ?? '—' }}</div>
                        <div class="col-md-6"><strong class="text-muted small d-block">Apoderado</strong>{{ $student->nombre_apoderado ?? '—' }}</div>
                        <div class="col-md-6"><strong class="text-muted small d-block">Tel. apoderado</strong>{{ $student->telefono_apoderado ?? '—' }}</div>
                    </div>
                </div></div>
            </div>
            <div class="tab-pane fade" id="tab-matriculas">
                <div class="card"><div class="table-responsive">
                    <table class="table align-middle mb-0 small">
                        <thead><tr><th>Periodo</th><th>Curso</th><th>Turno</th><th>Estado</th><th></th></tr></thead>
                        <tbody>
                            @forelse($student->enrollments as $e)
                            <tr>
                                <td>{{ $e->periodo }}</td>
                                <td>{{ $e->course->nombre }}</td>
                                <td>{{ $e->turno }}</td>
                                <td>{!! $e->estado_badge !!}</td>
                                <td><a href="{{ route('enrollments.show', $e) }}" class="btn btn-xs btn-outline-primary" style="font-size:0.75rem;padding:2px 8px">Ver</a></td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted py-3">Sin matrículas registradas</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div></div>
            </div>
            <div class="tab-pane fade" id="tab-pagos">
                <div class="card"><div class="table-responsive">
                    <table class="table align-middle mb-0 small">
                        <thead><tr><th>Fecha</th><th>Concepto</th><th>Periodo</th><th>Forma</th><th>Total</th><th>Estado</th></tr></thead>
                        <tbody>
                            @forelse($student->payments->sortByDesc('fecha_pago') as $p)
                            <tr>
                                <td>{{ $p->fecha_pago->format('d/m/Y') }}</td>
                                <td>{{ $p->tipo_pago }}</td>
                                <td>{{ $p->periodo_pago ?? '—' }}</td>
                                <td>{{ $p->forma_pago }}</td>
                                <td class="fw-bold text-success">S/ {{ number_format($p->total,2) }}</td>
                                <td>{!! $p->estado_badge !!}</td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-muted py-3">Sin pagos registrados</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div></div>
            </div>
            <div class="tab-pane fade" id="tab-comprobantes">
                <div class="card"><div class="table-responsive">
                    <table class="table align-middle mb-0 small">
                        <thead><tr><th>Comprobante</th><th>Tipo</th><th>Fecha</th><th>Total</th><th>SUNAT</th><th></th></tr></thead>
                        <tbody>
                            @forelse($student->sales->sortByDesc('fecha_emision') as $sale)
                            <tr>
                                <td><code>{{ $sale->numero_comprobante }}</code></td>
                                <td>{{ $sale->tipo_nombre }}</td>
                                <td>{{ $sale->fecha_emision->format('d/m/Y') }}</td>
                                <td class="fw-bold">S/ {{ number_format($sale->mto_imp_venta,2) }}</td>
                                <td>{!! $sale->estado_badge !!}</td>
                                <td>
                                    <a href="{{ route('sales.pdf', $sale) }}" class="btn btn-xs btn-outline-danger" target="_blank" style="font-size:0.75rem;padding:2px 8px">
                                        <i class="bi bi-file-pdf"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-muted py-3">Sin comprobantes</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div></div>
            </div>
        </div>
    </div>
</div>
@endsection
