<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Redirigir raíz al dashboard
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Redirigir /home al dashboard
    Route::get('/home', function () {
        return redirect()->route('dashboard');
    })->name('home');

    // Alumnos
    Route::resource('alumnos', StudentController::class)->names('students');
    Route::get('/alumnos/{student}/pagos', [StudentController::class, 'payments'])->name('students.payments');

    // Cursos y categorías
    Route::resource('cursos', CourseController::class)->names('courses');
    Route::resource('categorias', CategoryController::class)->names('categories');

    // Matrículas
    Route::resource('matriculas', EnrollmentController::class)->names('enrollments');

    // Cobros
    Route::resource('cobros', PaymentController::class)->names('payments');
    Route::post('/cobros/{payment}/anular', [PaymentController::class, 'anular'])->name('payments.anular');

    // Comprobantes SUNAT
    Route::prefix('comprobantes')->name('sales.')->group(function () {
        Route::get('/', [SaleController::class, 'index'])->name('index');
        Route::get('/{sale}', [SaleController::class, 'show'])->name('show');
        Route::get('/{sale}/pdf', [SaleController::class, 'pdf'])->name('pdf');
        Route::get('/{sale}/xml', [SaleController::class, 'downloadXml'])->name('xml');
        Route::post('/{sale}/reenviar', [SaleController::class, 'reenviar'])->name('reenviar');
    });

    // Reportes
    Route::prefix('reportes')->name('reports.')->group(function () {
        Route::get('/ingresos', [ReportController::class, 'ingresos'])->name('ingresos');
        Route::get('/por-curso', [ReportController::class, 'porCurso'])->name('porCurso');
        Route::get('/por-cajero', [ReportController::class, 'porCajero'])->name('porCajero');
        Route::get('/pendientes-sunat', [ReportController::class, 'pendientesSunat'])->name('pendientesSunat');
        Route::get('/morosos', [ReportController::class, 'morosos'])->name('morosos');
    });

    // Configuración y Usuarios (solo superadmin)
    Route::middleware('role:superadmin')->group(function () {
        Route::get('/configuracion', [ConfigController::class, 'edit'])->name('config.edit');
        Route::put('/configuracion', [ConfigController::class, 'update'])->name('config.update');
        Route::resource('usuarios', UserController::class)->names('users');
        Route::post('/usuarios/{user}/toggle', [UserController::class, 'toggle'])->name('users.toggle');
    });

    // API interna AJAX
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/alumnos/buscar', [StudentController::class, 'buscar'])->name('students.buscar');
        Route::get('/alumnos/{student}/matriculas', [StudentController::class, 'matriculasActivas'])->name('students.matriculas');
        Route::get('/cursos/{course}/precio', [CourseController::class, 'precio'])->name('courses.precio');
    });
});
