<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class ConfigController extends Controller
{

    public function edit()
    {
        $company = Company::first();
        return view('config.edit', compact('company'));
    }

    public function update(Request $request)
    {
        $company = Company::first();

        $data = $request->validate([
            'razon_social'    => 'required|string|max:200',
            'nombre_comercial'=> 'nullable|string|max:200',
            'ubigeo'          => 'required|string|size:6',
            'departamento'    => 'required|string|max:100',
            'provincia'       => 'required|string|max:100',
            'distrito'        => 'required|string|max:100',
            'direccion'       => 'required|string|max:255',
            'urbanizacion'    => 'nullable|string|max:100',
            'telefono'        => 'nullable|string|max:20',
            'email'           => 'nullable|email|max:100',
            'serie_boleta'    => 'required|string|size:4',
            'serie_factura'   => 'required|string|size:4',
        ]);

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
            $data['logo_path'] = $logoPath;
        }

        $company->update($data);

        return redirect()->route('config.edit')
            ->with('success', 'Configuración actualizada correctamente.');
    }
}
