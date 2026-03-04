<?php

namespace App\Jobs;

use App\Models\Sale;
use App\Services\SunatService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendInvoiceToSunat implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    public int $tries = 3;
    public string $queue = 'sunat';

    public function __construct(public Sale $sale) {}

    public function handle(SunatService $sunatService): void
    {
        $this->sale->load(['company', 'student', 'items', 'payment']);
        $sunatService->emitir($this->sale);
    }

    public function failed(\Throwable $exception): void
    {
        $this->sale->update([
            'estado_sunat'       => 'PENDIENTE',
            'sunat_descripcion'  => 'Error en cola: ' . $exception->getMessage(),
        ]);
    }
}
