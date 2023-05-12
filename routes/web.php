<?php

use App\Http\Controllers\TallerController;
use App\Http\Controllers\UsuariController;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Models\Usuari;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('login');
})->middleware('guest')->name('/');


Route::get('/login-google', function () {
    return Socialite::driver('google')->redirect();
});

Route::get('/google-callback', function (Request $request) {

    try {
        $user = Socialite::driver('google')->user();
    } catch (\Exception $e) {
        return redirect()->route('/')->withErrors('Error al obtenir les dades del usuari.');
    }


    //verificar si el usuario existe en la base de datos y si no existe redirigir a la ruta "/" con un mensaje de error y si existe loguearlo
    $userDB = Usuari::where('email', $user->email)->first();

    if (!$userDB) {
        return redirect()->route('/')->withErrors('No tens permís per accedir a aquesta aplicació.');
    }


    Auth::login($userDB);

    $request->session()->put('avatarUrl', $user->getAvatar());

    // redireccionar al home
    return redirect()->route('home');
});


Auth::routes();

Route::get('/home', [TallerController::class, 'index'])->middleware(['auth', 'invalidpage'])->name('home');


Route::get('/logout', function () {
    Auth::logout();
    return redirect()->route('/');
})->name('logout');


Route::get('/assignar', [UsuariController::class, 'assignar'])->middleware(['auth', 'isSuperAdmin'])->name('assignar');

Route::post('/assignar', [UsuariController::class, 'canviarAdmin'])->middleware(['auth', 'isSuperAdmin'])->name('assignar');

Route::delete('/esborrartaller/{codi}', [TallerController::class, 'esborrar'])->middleware('auth')->name('esborrartaller');

Route::post('/editarTaller/{codi}', [TallerController::class, 'editarTaller'])->middleware('auth')->name('editarTaller');

Route::post('/dadesTaller', [TallerController::class, 'dadesTaller'])->middleware('auth')->name('dadesTaller');

Route::post('/creartaller', [TallerController::class, 'creartaller'])->middleware('auth')->name('creartaller');

Route::post('/participar/{codi}', [TallerController::class, 'participar'])->middleware('auth')->name('participar');

Route::post('/desapuntarse/{codi}', [TallerController::class, 'desapuntarse'])->middleware('auth')->name('desapuntarse');

Route::post('/afegirParticipant/{codi}', [TallerController::class, 'afegirParticipant'])->middleware('auth', 'isAdmin')->name('afegirParticipant');

Route::post('/participantsTaller/{codi}', [TallerController::class, 'participantsTaller'])->middleware('auth', 'isAdmin')->name('participantsTaller');

Route::post('/eliminarParticipants/{codi}', [TallerController::class, 'eliminarParticipants'])->middleware('auth', 'isAdmin')->name('eliminarParticipants');

Route::get('/configuracio', [UsuariController::class, 'configuracio'])->middleware('auth', 'isSuperAdmin')->name('configuracio');

Route::post('/importar', [UsuariController::class, 'importar'])->middleware('auth', 'isSuperAdmin')->name('importar');