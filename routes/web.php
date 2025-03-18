<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NavigationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/auth.php'; //rutas de autenticacion 
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', function () {
    return redirect()->route('home');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/welcome-message', [NavigationController::class, 'getWelcomeMessage']);

Route::post('/register', [RegisteredUserController::class, 'store'])->name('register');
Route::get('/check-field', function (Request $request) {  //Ruta para verificar si existe el email o el username
    $field = $request->field;
    $value = $request->value;
    $exists = User::where($field, $value)->exists();
    return response()->json(['exists' => $exists]);
});

//Dentro de la aplicación
Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::post('/users/encrypt-message', [MessageController::class, 'encryptMessage'])->name('users.encrypt');
Route::post('/users/save-message', [MessageController::class, 'store'])->name('users.store');

Route::get('/unread-count', [NavigationController::class, 'countUnreadMessages'])->name('nav.unread.count')->middleware('auth');;

Route::middleware(['auth', 'web'])->group(function () {
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');

    // Ruta para guardar el mensaje en la base de datos (POST)
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');

    /* Route::get('/messages/{id}', [MessageController::class, 'show']); // Obtener y desencripta el mensaje */
    Route::post('/messages/received/{Id}/mark-as-read', [MessageController::class, 'markAsRead'])->name('messages.markAsRead'); // Marca como leído

    Route::get('/messages/received/{id}', [MessageController::class, 'showReceived'])->name('messages.showReceived');
    Route::get('/messages/sent/{id}', [MessageController::class, 'showSent'])->name('messages.showSent');

    // Ruta para cifrar el mensaje antes de confirmarlo (POST)
    Route::post('/encrypt-message', [MessageController::class, 'encryptMessage'])->name('messages.encrypt');
});

Route::get('/search-users', function (Request $request) {
    $query = $request->input('query');

    // Obtener el ID del usuario autenticado
    $usuarioAutenticado = Auth::id();

    // Buscar usuarios excluyendo al autenticado
    $usuarios = User::where('name', 'LIKE', "%{$query}%") // Buscar por nombre
        ->where('id', '!=', $usuarioAutenticado) // Excluir usuario autenticado
        ->limit(5)
        ->get(['id', 'name', 'lastName', 'email']); // Puedes añadir 'email' si lo necesitas

    return response()->json($usuarios);
})->name('search-users');
