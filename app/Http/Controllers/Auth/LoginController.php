<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home';

    /** Intentos máximos antes de bloquear */
    protected const MAX_ATTEMPTS = 5;

    /** Minutos de bloqueo tras superar el límite */
    protected const LOCKOUT_MINUTES = 15;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Valida el formulario y comprueba bloqueo antes de intentar autenticar.
     */
    protected function validateLogin(Request $request): void
    {
        $request->validate([
            $this->username() => ['required', 'string', 'email'],
            'password'        => ['required', 'string'],
        ]);

        // Comprobar bloqueo temporal a nivel de BD
        $user = User::where('email', $request->email)->first();

        if ($user && $user->bloqueado_hasta && $user->bloqueado_hasta->isFuture()) {
            $minutos = (int) now()->diffInMinutes($user->bloqueado_hasta, false) + 1;
            throw ValidationException::withMessages([
                $this->username() => [
                    "Cuenta bloqueada temporalmente. Intente de nuevo en {$minutos} minuto(s).",
                ],
            ]);
        }
    }

    /**
     * Intenta autenticar. Verifica activo ANTES de llamar a Auth::attempt().
     */
    protected function attemptLogin(Request $request): bool
    {
        $user = User::where('email', $request->email)->first();

        // Usuario existe pero está desactivado
        if ($user && ! $user->activo) {
            throw ValidationException::withMessages([
                $this->username() => [
                    'Esta cuenta ha sido desactivada. Contacta al administrador.',
                ],
            ]);
        }

        return $this->guard()->attempt(
            $this->credentials($request),
            $request->boolean('remember')
        );
    }

    /**
     * Login exitoso: limpiar intentos y registrar acceso.
     */
    protected function authenticated(Request $request, $user): void
    {
        $user->update([
            'login_intentos' => 0,
            'bloqueado_hasta' => null,
            'ultimo_acceso'   => now(),
            'ultimo_ip'       => $request->ip(),
        ]);
    }

    /**
     * Login fallido: incrementar contador y bloquear si llega al límite.
     */
    protected function sendFailedLoginResponse(Request $request): void
    {
        $user = User::where('email', $request->email)->first();

        if ($user) {
            $intentos = $user->login_intentos + 1;

            if ($intentos >= self::MAX_ATTEMPTS) {
                $user->update([
                    'login_intentos'  => $intentos,
                    'bloqueado_hasta' => now()->addMinutes(self::LOCKOUT_MINUTES),
                ]);

                throw ValidationException::withMessages([
                    $this->username() => [
                        'Demasiados intentos fallidos. Cuenta bloqueada por ' . self::LOCKOUT_MINUTES . ' minutos.',
                    ],
                ]);
            }

            $user->update(['login_intentos' => $intentos]);
            $restantes = self::MAX_ATTEMPTS - $intentos;

            throw ValidationException::withMessages([
                $this->username() => [
                    "Credenciales incorrectas. Te quedan {$restantes} intento(s) antes del bloqueo.",
                ],
            ]);
        }

        // Email no existe: mensaje genérico (no revelar si el correo está registrado)
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }
}
