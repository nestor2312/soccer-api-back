<?php

use Illuminate\Support\Facades\Route;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



// Route::get('/test-resend', function () {
//     try {
//         Mail::raw('¡Hola! Este es un correo de prueba enviado con Resend desde Laravel.', function ($message) {
//             $message->to('nestorcanal01@gmail.com')
//                     ->subject('Prueba de Resend Laravel');
//         });

//         return '✅ Correo enviado. Revisa tu panel de Resend.';
//     } catch (\Exception $e) {
//         return '❌ Error: ' . $e->getMessage();
//     }
// });

Route::get('/test-mail', function () {
    // Buscas un usuario real de tu base de datos
    $usuario = User::first(); // O puedes buscarlo por ID con User::find(1)

    if (!$usuario) {
        return 'No hay usuarios registrados aún.';
    }

    // Envías el correo
    Mail::to($usuario->email)->send(new WelcomeMail($usuario));

    return "Correo enviado a {$usuario->email}";
});

// Route::get('/preview-vista', function () {
//     Mail::to('ruebahost9@gmail.com')->send(new WelcomeMail);
//     return "✅ Correo de prueba enviado correctamente";
// });


Route::get('/', function () {
    return view('welcome');
});

Route::get('/a', function () {
    return view('emails.EndDemo');
});

Route::get('/b', function () {
    return view('emails.welcome');
});



Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
