@extends('layouts.app')
@section('title', 'Configuración')
@section('breadcrumb')
    <li class="breadcrumb-item active">Configuración</li>
@endsection
@section('content')
<div class="row justify-content-center"><div class="col-md-9">
<div class="card">
    <div class="card-header bg-primary text-white fw-semibold"><i class="bi bi-gear me-2"></i>Configuración de la Academia</div>
    <div class="card-body">
        <form method="POST" action="{{ route('config.update') }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-12"><h6 class="text-muted text-uppercase border-bottom pb-1" style="font-size:0.72rem;letter-spacing:1px">Datos de la empresa</h6></div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">RUC <span class="text-muted">(solo lectura)</span></label>
                    <input type="text" value="{{ $company->ruc }}" class="form-control bg-light" readonly>
                </div>
                <div class="col-md-8">
                    <label class="form-label small fw-semibold">Razón Social *</label>
                    <input type="text" name="razon_social" value="{{ old('razon_social',$company->razon_social) }}" class="form-control @error('razon_social') is-invalid @enderror" required>
                    @error('razon_social')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Nombre comercial</label>
                    <input type="text" name="nombre_comercial" value="{{ old('nombre_comercial',$company->nombre_comercial) }}" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Email</label>
                    <input type="email" name="email" value="{{ old('email',$company->email) }}" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Teléfono</label>
                    <input type="text" name="telefono" value="{{ old('telefono',$company->telefono) }}" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Logo</label>
                    <input type="file" name="logo" class="form-control" accept="image/*">
                    @if($company->logo_path)
                    <img src="{{ asset('storage/'.$company->logo_path) }}" height="40" class="mt-1">
                    @endif
                </div>

                <div class="col-12"><h6 class="text-muted text-uppercase border-bottom pb-1 mt-2" style="font-size:0.72rem;letter-spacing:1px">Dirección</h6></div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold">Ubigeo *</label>
                    <input type="text" name="ubigeo" value="{{ old('ubigeo',$company->ubigeo) }}" class="form-control @error('ubigeo') is-invalid @enderror" maxlength="6" required>
                    @error('ubigeo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Departamento *</label>
                    <input type="text" name="departamento" value="{{ old('departamento',$company->departamento) }}" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Provincia *</label>
                    <input type="text" name="provincia" value="{{ old('provincia',$company->provincia) }}" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Distrito *</label>
                    <input type="text" name="distrito" value="{{ old('distrito',$company->distrito) }}" class="form-control" required>
                </div>
                <div class="col-md-8">
                    <label class="form-label small fw-semibold">Dirección *</label>
                    <input type="text" name="direccion" value="{{ old('direccion',$company->direccion) }}" class="form-control @error('direccion') is-invalid @enderror" required>
                    @error('direccion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Urbanización</label>
                    <input type="text" name="urbanizacion" value="{{ old('urbanizacion',$company->urbanizacion) }}" class="form-control">
                </div>

                <div class="col-12"><h6 class="text-muted text-uppercase border-bottom pb-1 mt-2" style="font-size:0.72rem;letter-spacing:1px">Series SUNAT</h6></div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Serie Boleta *</label>
                    <input type="text" name="serie_boleta" value="{{ old('serie_boleta',$company->serie_boleta) }}" class="form-control" maxlength="4" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Serie Factura *</label>
                    <input type="text" name="serie_factura" value="{{ old('serie_factura',$company->serie_factura) }}" class="form-control" maxlength="4" required>
                </div>
            </div>
            <div class="d-flex gap-2 justify-content-end mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check2 me-1"></i>Guardar Configuración</button>
            </div>
        </form>
    </div>
</div>
</div></div>
@endsection
