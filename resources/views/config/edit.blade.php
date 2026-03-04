@extends('layouts.app')
@section('title', 'Configuración')
@section('breadcrumb')
    <li class="breadcrumb-item active">Configuración</li>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h5><i class="bi bi-gear text-primary"></i> Configuración</h5>
        <div class="page-subtitle">Datos de la academia y parámetros SUNAT</div>
    </div>
</div>

<div class="row justify-content-center">
<div class="col-xl-9 col-lg-10">
<div class="card">
    <div class="card-hdr">
        <div class="card-hdr-icon blue"><i class="bi bi-building-gear"></i></div>
        <div>
            <div class="card-hdr-title">Configuración de la Academia</div>
            <div class="card-hdr-sub">Información fiscal y parámetros de comprobantes electrónicos</div>
        </div>
    </div>
    <div class="card-body p-4">
        <form method="POST" action="{{ route('config.update') }}" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="fsec">
                <div class="fsec-hdr">
                    <span class="fsec-num">1</span>
                    <span class="fsec-label">Datos de la empresa</span>
                    <i class="bi bi-building fsec-icon ms-1"></i>
                </div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">RUC <span class="text-muted fw-normal">(solo lectura)</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-hash"></i></span>
                            <input type="text" value="{{ $company->ruc }}" class="form-control bg-light" readonly>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Razón Social <span class="text-danger">*</span></label>
                        <input type="text" name="razon_social" value="{{ old('razon_social',$company->razon_social) }}"
                               class="form-control @error('razon_social') is-invalid @enderror" required>
                        @error('razon_social')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nombre comercial</label>
                        <input type="text" name="nombre_comercial" value="{{ old('nombre_comercial',$company->nombre_comercial) }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" value="{{ old('email',$company->email) }}" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Teléfono</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                            <input type="text" name="telefono" value="{{ old('telefono',$company->telefono) }}" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Logo</label>
                        <input type="file" name="logo" class="form-control" accept="image/*">
                        @if($company->logo_path)
                        <div class="mt-2">
                            <img src="{{ asset('storage/'.$company->logo_path) }}" height="40" class="rounded border p-1">
                            <small class="text-muted ms-2">Logo actual</small>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="fsec">
                <div class="fsec-hdr">
                    <span class="fsec-num">2</span>
                    <span class="fsec-label">Dirección fiscal</span>
                    <i class="bi bi-geo-alt fsec-icon ms-1"></i>
                </div>
                <div class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label">Ubigeo <span class="text-danger">*</span></label>
                        <input type="text" name="ubigeo" value="{{ old('ubigeo',$company->ubigeo) }}"
                               class="form-control @error('ubigeo') is-invalid @enderror" maxlength="6" required placeholder="150101">
                        @error('ubigeo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Departamento <span class="text-danger">*</span></label>
                        <input type="text" name="departamento" value="{{ old('departamento',$company->departamento) }}" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Provincia <span class="text-danger">*</span></label>
                        <input type="text" name="provincia" value="{{ old('provincia',$company->provincia) }}" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Distrito <span class="text-danger">*</span></label>
                        <input type="text" name="distrito" value="{{ old('distrito',$company->distrito) }}" class="form-control" required>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Dirección <span class="text-danger">*</span></label>
                        <input type="text" name="direccion" value="{{ old('direccion',$company->direccion) }}"
                               class="form-control @error('direccion') is-invalid @enderror" required>
                        @error('direccion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Urbanización</label>
                        <input type="text" name="urbanizacion" value="{{ old('urbanizacion',$company->urbanizacion) }}" class="form-control">
                    </div>
                </div>
            </div>

            <div class="fsec">
                <div class="fsec-hdr">
                    <span class="fsec-num">3</span>
                    <span class="fsec-label">Series SUNAT</span>
                    <i class="bi bi-file-earmark-check fsec-icon ms-1"></i>
                </div>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Serie Boleta <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">B</span>
                            <input type="text" name="serie_boleta" value="{{ old('serie_boleta',$company->serie_boleta) }}"
                                   class="form-control" maxlength="4" required placeholder="001">
                        </div>
                        <small class="text-muted">Ej: B001</small>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Serie Factura <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">F</span>
                            <input type="text" name="serie_factura" value="{{ old('serie_factura',$company->serie_factura) }}"
                                   class="form-control" maxlength="4" required placeholder="001">
                        </div>
                        <small class="text-muted">Ej: F001</small>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 justify-content-end pt-2 border-top mt-2">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check2 me-1"></i>Guardar Configuración
                </button>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
