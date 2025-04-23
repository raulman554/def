<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/* -----------------------------------------
|  Controladores de usuario
|------------------------------------------*/
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\PagoController;

/* -----------------------------------------
|  Controladores de administración
|------------------------------------------*/
use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TipoSorteoController;
use App\Http\Controllers\Admin\NivelPremioController;
use App\Http\Controllers\Admin\InstanciaSorteoController;
use App\Http\Controllers\Admin\RetiroController;

/*
|--------------------------------------------------------------------------
| Ruta pública de bienvenida
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => view('welcome'))->name('home');


/*
|--------------------------------------------------------------------------
| RUTAS DE AUTENTICACIÓN (USUARIO)
|--------------------------------------------------------------------------
*/
// Registro
Route::get ('register', [RegisterController::class,'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class,'register']);
// Login / Logout
Route::get ('login',    [LoginController::class,'showLoginForm'])->name('login');
Route::post('login',    [LoginController::class,'login']);
Route::post('logout',   [LoginController::class,'logout'])->name('logout');
// Recuperación de contraseña (stub)
Route::get ('password/reset', fn (Request $r) => view('auth.passwords.email_stub'))->name('password.request');
Route::post('password/email', fn (Request $r) => back()->with('status','En desarrollo.'))->name('password.email');


/*
|--------------------------------------------------------------------------
| ÁREA DE USUARIO (autenticado)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:usuario')->group(function () {
    // Perfil
    Route::get ('mi-perfil',        [UsuarioController::class,'show'])->name('mi-perfil');
    Route::get ('mi-perfil/editar', [UsuarioController::class,'edit'])->name('mi-perfil.editar');
    Route::post('mi-perfil/editar', [UsuarioController::class,'update'])->name('mi-perfil.update');

    // Sorteos
    Route::get ('sorteos',                [TicketController::class,'index'])->name('sorteos.index');
    Route::get ('sorteos/{id}',           [TicketController::class,'show'])->name('sorteos.show');
    Route::post('sorteos/{id}/reservar',  [TicketController::class,'reservar'])->name('sorteos.reservar');
    Route::get ('sorteos/{id}/pago',      [TicketController::class,'pago'])->name('sorteos.pago');
    Route::post('sorteos/{id}/comprobante',[TicketController::class,'subirComprobante'])->name('sorteos.comprobante');
    Route::get ('mis-tickets',            [TicketController::class,'misTickets'])->name('mis-tickets');

    // Solicitudes de retiro
    Route::get ('retiro/solicitar', [PagoController::class,'showRetiroForm'])->name('retiro.solicitar');
    Route::post('retiro/solicitar', [PagoController::class,'solicitarRetiro'])->name('retiro.store');
});


/*
|--------------------------------------------------------------------------
| LOGIN DE ADMINISTRACIÓN
|--------------------------------------------------------------------------
*/
Route::get ('admin/login',  [AdminLoginController::class,'showLoginForm'])->name('admin.login');
Route::post('admin/login',  [AdminLoginController::class,'login'])->name('admin.login.submit');
Route::post('admin/logout', [AdminLoginController::class,'logout'])->name('admin.logout');


/*
|--------------------------------------------------------------------------
| ÁREA PROTEGIDA DE ADMINISTRACIÓN
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
      ->middleware(['auth:administrador','is_admin'])
      ->group(function () {

    // Dashboard
    Route::get('dashboard', [DashboardController::class,'index'])->name('admin.dashboard');

    // Tickets pendientes de pago
    Route::get ('tickets/pendientes', [PagoController::class,'ticketsPendientes'])->name('admin.tickets.pendientes');
    Route::post('tickets/{id}/aprobar', [PagoController::class,'aprobar'])->name('admin.tickets.aprobar');
    Route::post('tickets/{id}/rechazar',[PagoController::class,'rechazar'])->name('admin.tickets.rechazar');

    // CRUD de Tipos de Sorteo
    Route::resource('tipos-sorteo', TipoSorteoController::class);

// CRUD anidado de Niveles de Premio para cada Tipo de Sorteo
Route::prefix('tipos-sorteo/{tipo}')
     ->name('tipos-sorteo.')
     ->group(function () {
         Route::resource('niveles-premio', NivelPremioController::class)
              ->parameters(['niveles-premio' => 'niveles_premio'])
              ->except(['show']);
     });

    // CRUD de Instancias de Sorteo
    Route::resource('instancias-sorteo', InstanciaSorteoController::class);

// Gestión de retiros
    Route::resource('retiros', RetiroController::class)
         ->only(['index','update','destroy']);
});