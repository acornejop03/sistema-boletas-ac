<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(private PaymentService $paymentService) {}

    public function index(Request $request)
    {
        $this->authorize('ver cobros');

        $query = Payment::with(['student', 'sale', 'user', 'enrollment.course']);

        // El cajero solo ve sus propios cobros
        if (auth()->user()->hasRole('cajero')) {
            $query->where('user_id', auth()->id());
        } elseif (!auth()->user()->can('ver cobros otros usuarios')) {
            $query->where('user_id', auth()->id());
        }

        if ($request->filled('fecha_desde')) {
            $query->where('fecha_pago', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->where('fecha_pago', '<=', $request->fecha_hasta);
        }
        if ($request->filled('tipo_pago')) {
            $query->where('tipo_pago', $request->tipo_pago);
        }
        if ($request->filled('forma_pago')) {
            $query->where('forma_pago', $request->forma_pago);
        }
        if ($request->filled('estado')) {
            $query->where('estado_pago', $request->estado);
        }

        $payments  = $query->latest()->paginate(25)->withQueryString();
        $totalDia  = $query->where('fecha_pago', now()->toDateString())
                           ->where('estado_pago', 'PAGADO')
                           ->sum('total');

        return view('payments.index', compact('payments', 'totalDia'));
    }

    public function create()
    {
        $this->authorize('crear cobros');
        return view('payments.create');
    }

    public function store(StorePaymentRequest $request)
    {
        try {
            $result = $this->paymentService->cobrar($request->validated(), auth()->user());

            return redirect()
                ->route('payments.show', $result['payment'])
                ->with('success', 'Cobro registrado y comprobante emitido correctamente.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error al registrar el cobro: ' . $e->getMessage());
        }
    }

    public function show(Payment $payment)
    {
        $this->authorize('ver cobros');
        $payment->load(['student', 'sale.items', 'sale.sunatResponses', 'user', 'enrollment.course']);
        return view('payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        abort(403, 'Los cobros no se pueden editar, solo anular.');
    }

    public function update(Request $request, Payment $payment)
    {
        abort(403);
    }

    public function destroy(Payment $payment)
    {
        abort(403, 'Use la función de anular.');
    }

    public function anular(Request $request, Payment $payment)
    {
        $this->authorize('anular cobros');

        $request->validate(['motivo' => 'required|string|max:500']);

        try {
            $this->paymentService->anular($payment, $request->motivo, auth()->user());
            return back()->with('success', 'Cobro anulado correctamente.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
